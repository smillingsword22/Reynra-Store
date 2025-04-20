<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\ProdukModel;
use App\Models\PengeluaranModel;
use App\Models\DetailPenjualanModel;

class Home extends BaseController
{
    protected $penjualanModel;
    protected $produkModel;
    protected $pengeluaranModel;
    protected $detailPenjualanModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->produkModel = new ProdukModel();
        $this->pengeluaranModel = new PengeluaranModel();
        $this->detailPenjualanModel = new DetailPenjualanModel();
    }

    public function index()
    {
        // Hitung Total Penjualan
        $totalPenjualan = $this->penjualanModel->selectSum('total')->selectSum('keuntungan')->first();
        $totalKeuntungan = $totalPenjualan ? $totalPenjualan['keuntungan'] : 0;
        $totalPenjualan = $totalPenjualan ? $totalPenjualan['total'] : 0;


        // Hitung Jumlah Produk Terjual
        $jumlahProdukTerjual = $this->detailPenjualanModel->selectSum('jumlah')->first();
        $jumlahProdukTerjual = $jumlahProdukTerjual ? $jumlahProdukTerjual['jumlah'] : 0;

        // Hitung Total Pengeluaran
        $totalPengeluaran = $this->pengeluaranModel->selectSum('jumlah')->first();
        $totalPengeluaran = $totalPengeluaran ? $totalPengeluaran['jumlah'] : 0;

        // Ambil data sebelumnya untuk perbandingan (misalnya dari bulan lalu)
        $previousData = $this->getPreviousData();

        // Persentase perubahan
        $persenPenjualan = $this->calculatePercentage($totalPenjualan, $previousData['penjualan']);
        $persenKeuntungan = $this->calculatePercentage($totalKeuntungan, $previousData['keuntungan']);
        $persenPengeluaran = $this->calculatePercentage($totalPengeluaran, $previousData['pengeluaran']);

        // Menghitung perubahan (peningkatan)
        $extraPenjualan = $totalPenjualan - $previousData['penjualan'];
        $extraKeuntungan = $totalKeuntungan - $previousData['keuntungan'];
        $extraPengeluaran = $totalPengeluaran - $previousData['pengeluaran'];

        return view('dashboard_view', [
            'totalPenjualan' => $totalPenjualan,
            'totalKeuntungan' => $totalKeuntungan,
            'jumlahProdukTerjual' => $jumlahProdukTerjual,
            'totalPengeluaran' => $totalPengeluaran,
            'persenPenjualan' => $persenPenjualan,
            'persenKeuntungan' => $persenKeuntungan,
            'persenPengeluaran' => $persenPengeluaran,
            'extraPenjualan' => $extraPenjualan,
            'extraKeuntungan' => $extraKeuntungan,
            'extraPengeluaran' => $extraPengeluaran
        ]);
    }

    // Fungsi untuk menghitung persentase perubahan
    private function calculatePercentage($current, $previous)
    {
        // Debug dulu
        // var_dump($current, $previous); die;

        // Cast ke float biar aman
        $current = (float) $current;
        $previous = (float) $previous;

        if ($previous == 0) {
            return 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }


    // Fungsi untuk mengambil data sebelumnya (misalnya bulan lalu)
    private function getPreviousData()
    {
        // Ambil total penjualan bulan lalu
        $previousPenjualan = $this->penjualanModel
                                ->where('MONTH(tanggal)', date('m') - 1)
                                ->selectSum('total')
                                ->first();
        $previousPenjualan = $previousPenjualan ? $previousPenjualan['total'] : 0;

        // Ambil keuntungan bulan lalu
        $previousKeuntungan = $this->penjualanModel
                            ->selectSum('keuntungan')
                            ->where('MONTH(tanggal)', date('m') - 1)
                            ->first();
        $previousKeuntungan = $previousKeuntungan ? $previousKeuntungan['keuntungan'] : 0;



        // Ambil total pengeluaran bulan lalu
        $previousPengeluaran = $this->pengeluaranModel
                                    ->where('MONTH(tanggal)', date('m') - 1)
                                    ->selectSum('jumlah')
                                    ->first();
        $previousPengeluaran = $previousPengeluaran ? $previousPengeluaran['jumlah'] : 0;

        return [
            'penjualan' => $previousPenjualan,
            'keuntungan' => $previousKeuntungan,
            'pengeluaran' => $previousPengeluaran
        ];
    }
}
