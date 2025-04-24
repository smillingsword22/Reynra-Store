<?php

namespace App\Controllers\Pribadi;

use App\Models\TransaksiModel;
use App\Models\KategoriTransaksiModel;
use App\Controllers\BaseController;
use CodeIgniter\Controller;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $kategoriTransaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->kategoriTransaksiModel  = new KategoriTransaksiModel();
        $this->db = \Config\Database::connect();  // Mengakses database
    }

    // Menampilkan daftar transaksi
    public function index()
    {
        // Mengambil data transaksi dari database
        $transaksi = $this->transaksiModel
        ->select('transaksi.*, kategori_transaksi.nama as kategori_nama')
        ->join('kategori_transaksi', 'kategori_transaksi.id = transaksi.kategori_id')
        ->findAll();
        
        // Mengambil data kategori transaksi dari database
        $kategori_transaksi = $this->kategoriTransaksiModel->findAll();

        // Mengambil data saldo pribadi untuk ditampilkan
        $saldo = $this->db->table('saldo_pribadi')->get()->getRow();

        // Mengirimkan data ke view
        return view('pribadi/transaksi/index', [
            'transaksi' => $transaksi,
            'kategori_transaksi' => $kategori_transaksi,
            'saldo' => $saldo
        ]);
    }

    // Menyimpan transaksi
    public function store()
    {
        // Validasi input
        $validation = \Config\Services::validation();

        if (!$this->validate([
            'tanggal'    => 'required|valid_date',
            'kategori_id'=> 'required|is_not_unique[kategori_transaksi.id]',
            'tipe'       => 'required|in_list[masuk,keluar]',
            'jumlah'     => 'required|decimal',
            'keterangan' => 'permit_empty|string'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Ambil data dari form
        $data = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'kategori_id'=> $this->request->getPost('kategori_id'),
            'tipe'       => $this->request->getPost('tipe'),
            'jumlah'     => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        // Simpan transaksi ke database
        $this->transaksiModel->save($data);

        // Update saldo pribadi setelah transaksi disimpan
        $this->updateSaldo($data['jumlah'], $data['tipe']);

        return redirect()->to('/pribadi/transaksi')->with('message', 'Transaksi berhasil disimpan!');
    }

    // Fungsi untuk update saldo berdasarkan jenis transaksi
    private function updateSaldo($jumlah, $tipe)
    {
        // Ambil saldo saat ini dari tabel saldo_pribadi
        $saldo = $this->db->table('saldo_pribadi')->get()->getRow();

        // Logika pembaruan saldo berdasarkan jenis transaksi
        if ($tipe == 'masuk') {
            // Tambahkan jumlah ke saldo
            $saldo_baru = $saldo->saldo + $jumlah;
        } else {
            // Kurangi saldo dengan jumlah
            $saldo_baru = $saldo->saldo - $jumlah;
        }

        // Update saldo di tabel saldo_pribadi
        $this->db->table('saldo_pribadi')->update(['saldo' => $saldo_baru]);
    }

    // Menampilkan form untuk mengedit transaksi
    public function edit($id)
    {
        $transaksi = $this->transaksiModel->find($id);
        $kategori  = $this->kategoriTransaksiModel->findAll();

        if (!$transaksi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan');
        }

        return view('transaksi/edit', [
            'transaksi' => $transaksi,
            'kategori'  => $kategori
        ]);
    }

    // Update transaksi
    public function update($id)
    {
        $validation =  \Config\Services::validation();

        if (!$this->validate([
            'tanggal'    => 'required|valid_date',
            'kategori_id'=> 'required|is_not_unique[kategori_transaksi.id]',
            'tipe'       => 'required|in_list[masuk,keluar]',
            'jumlah'     => 'required|decimal',
            'keterangan' => 'permit_empty|string'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'kategori_id'=> $this->request->getPost('kategori_id'),
            'tipe'       => $this->request->getPost('tipe'),
            'jumlah'     => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        $this->transaksiModel->update($id, $data);

        return redirect()->to('/transaksi')->with('message', 'Transaksi berhasil diupdate!');
    }

    // Hapus transaksi
    public function delete($id)
    {
        $this->transaksiModel->delete($id);

        return redirect()->to('/transaksi')->with('message', 'Transaksi berhasil dihapus!');
    }
}
