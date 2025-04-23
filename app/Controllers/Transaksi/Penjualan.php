<?php

namespace App\Controllers\Transaksi;

use App\Controllers\BaseController;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\ProdukModel;

class Penjualan extends BaseController
{
    protected $penjualanModel;
    protected $detailPenjualanModel;
    protected $produkModel;

    public function __construct()
    {
        // Load models
        $this->db = \Config\Database::connect();
        $this->penjualanModel = new PenjualanModel();
        $this->detailPenjualanModel = new DetailPenjualanModel();
        $this->produkModel = new ProdukModel();
    }

    // Halaman utama untuk melihat penjualan
    public function index()
    {
        $penjualanModel = new PenjualanModel();
        $produkModel = new ProdukModel();

        $data = [
            'penjualan' => $penjualanModel->findAll(),
            'produk' => $produkModel->findAll()
        ];

        return view('transaksi/penjualan/index', $data);
    }

    public function detail($id)
    {
        $penjualanDetailModel = new DetailPenjualanModel();
        $produkModel = new ProdukModel();

        // Ambil data detail penjualan
        $details = $penjualanDetailModel->where('penjualan_id', $id)->findAll();

        // Generate tabel untuk detail
        $output = '<table class="table table-bordered">';
        $output .= '<thead><tr><th>Nama Produk</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr></thead><tbody>';

        foreach ($details as $detail) {
            $produk = $produkModel->find($detail['produk_id']);
            $output .= '<tr>';
            $output .= '<td>' . esc($produk['nama_produk']) . '</td>';
            $output .= '<td>' . $detail['jumlah'] . '</td>';
            $output .= '<td>Rp' . number_format($detail['harga'], 0, ',', '.') . '</td>';
            $output .= '<td>Rp' . number_format($detail['subtotal'], 0, ',', '.') . '</td>';
            $output .= '</tr>';
        }

        $output .= '</tbody></table>';
        return $output;
    }

    // Menyimpan transaksi penjualan dan detailnya
    public function create()
    {
        // Ambil data produk dari form
        $produk = $this->request->getPost('produk_id');  // Produk ID
        $qty = $this->request->getPost('qty');  // Jumlah

        // Debug untuk melihat data yang diterima
        // dd($produk, $qty);
        
        // Cek jika produk kosong atau tidak sesuai
        if (empty($produk) || !is_array($produk)) {
            return redirect()->back()->with('error', 'Data produk tidak ditemukan!');
        }

        $tanggal = $this->request->getPost('tanggal');
        $keterangan = $this->request->getPost('keterangan');
        $metode_pembayaran = $this->request->getPost('metode_pembayaran');

        $total = 0;
        $total_modal = 0;

        // Loop untuk setiap produk yang dipilih
        foreach ($produk as $key => $prod_id) {
            // Ambil data produk dari database
            $prod = $this->produkModel->find($prod_id);

            // Pastikan produk ada dalam database
            if ($prod) {
                // Menghitung subtotal dan total modal
                $harga = $prod['harga_jual'];
                $subtotal = $harga * $qty[$key];  // Ambil qty sesuai dengan index

                $total += $subtotal;
                $total_modal += ($prod['harga_beli'] * $qty[$key]);
            } else {
                log_message('error', 'Produk ID ' . $prod_id . ' tidak ditemukan.');
            }
        }

        // Hitung keuntungan
        $keuntungan = $total - $total_modal;

        // Simpan data penjualan
        $penjualan_id = $this->penjualanModel->insert([
            'tanggal' => $tanggal,
            'total' => $total,
            'keuntungan' => $keuntungan,
            'keterangan' => $keterangan,
            'metode_pembayaran' => $metode_pembayaran
        ]);

        if (!$penjualan_id) {
            log_message('error', 'Gagal menyimpan penjualan.');
            return redirect()->back()->with('error', 'Gagal menyimpan penjualan.');
        }

        // Simpan detail produk penjualan
        foreach ($produk as $key => $prod_id) {
            $prod = $this->produkModel->find($prod_id);
            if ($prod) {
                $this->detailPenjualanModel->insert([
                    'penjualan_id' => $penjualan_id,
                    'produk_id' => $prod_id,
                    'harga' => $prod['harga_jual'],
                    'jumlah' => $qty[$key],
                    'subtotal' => $prod['harga_jual'] * $qty[$key]
                ]);
            }
        }

        // Update saldo_store
        $saldoRow = $this->db->table('saldo_store')->get()->getRow();
        $saldoLama = $saldoRow ? $saldoRow->saldo : 0;
        $saldoBaru = $saldoLama + $total - $total_modal;

        // Update saldo_store table
        $this->db->table('saldo_store')->update(['saldo' => $saldoBaru], ['id' => 1]);

        // Simpan log perubahan saldo (opsional)
        $this->db->table('log_saldo_store')->insert([
            'keterangan' => 'Penjualan ID: ' . $penjualan_id,
            'perubahan' => $total - $total_modal,
            'saldo_sebelumnya' => $saldoLama,
            'saldo_setelah' => $saldoBaru,
        ]);

        // Jika berhasil, beri pesan sukses
        return redirect()->back()->with('success', 'Penjualan berhasil disimpan.');
    }

    // Menampilkan detail transaksi penjualan
    public function show($id)
    {
        $penjualan = $this->penjualanModel->find($id);
        $detailPenjualan = $this->detailPenjualanModel->where('penjualan_id', $id)->findAll();
        return view('transaksi/penjualan/show', [
            'penjualan' => $penjualan,
            'detailPenjualan' => $detailPenjualan
        ]);
    }

    public function delete($id)
    {
        // Cek apakah penjualan ada
        $penjualan = $this->penjualanModel->find($id);
        if (!$penjualan) {
            return redirect()->back()->with('error', 'Data penjualan tidak ditemukan.');
        }

        // Hapus detail penjualan terlebih dahulu
        $this->detailPenjualanModel->where('penjualan_id', $id)->delete();

        // Hapus data penjualan
        $this->penjualanModel->delete($id);

        return redirect()->back()->with('success', 'Data penjualan berhasil dihapus.');
    }
}
