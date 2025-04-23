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

        // Data history 10 terakhir (gabung dari 2 tabel)
        $riwayatPenjualan = $this->penjualanModel->select("'penjualan' as jenis, total as jumlah, tanggal")
            ->orderBy('tanggal', 'DESC')
            ->findAll(10);

        $riwayatPengeluaran = $this->pengeluaranModel->select("'pengeluaran' as jenis, jumlah, tanggal")
            ->orderBy('tanggal', 'DESC')
            ->findAll(10);
        
        // Gabung dan urutkan berdasarkan tanggal
        $riwayatGabung = array_merge($riwayatPenjualan, $riwayatPengeluaran);
        usort($riwayatGabung, function ($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        // Ambil 10 terbaru
        $riwayatGabung = array_slice($riwayatGabung, 0, 10);

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
            'extraPengeluaran' => $extraPengeluaran,
            'riwayat' => $riwayatGabung
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

    public function getData()
    {
        $type = $this->request->getGet('type'); // 'month' or 'year'

        $db = \Config\Database::connect();

        if ($type === 'month') {
            // Ambil data penjualan per hari bulan ini
            $builder = $db->table('penjualan')
                ->select("DAY(tanggal) as label, SUM(total) as total, SUM(keuntungan) as keuntungan")
                ->where("MONTH(tanggal)", date('m'))
                ->where("YEAR(tanggal)", date('Y'))
                ->groupBy("DAY(tanggal)")
                ->orderBy("DAY(tanggal)");

            $penjualanData = $builder->get()->getResultArray();

            $pengeluaranData = $db->table('pengeluaran')
                ->select("DAY(tanggal) as label, SUM(jumlah) as jumlah")
                ->where("MONTH(tanggal)", date('m'))
                ->where("YEAR(tanggal)", date('Y'))
                ->groupBy("DAY(tanggal)")
                ->orderBy("DAY(tanggal)")
                ->get()->getResultArray();

        } else {
            // Ambil data per bulan di tahun ini
            $builder = $db->table('penjualan')
                ->select("MONTH(tanggal) as label, SUM(total) as total, SUM(keuntungan) as keuntungan")
                ->where("YEAR(tanggal)", date('Y'))
                ->groupBy("MONTH(tanggal)")
                ->orderBy("MONTH(tanggal)");

            $penjualanData = $builder->get()->getResultArray();

            $pengeluaranData = $db->table('pengeluaran')
                ->select("MONTH(tanggal) as label, SUM(jumlah) as jumlah")
                ->where("YEAR(tanggal)", date('Y'))
                ->groupBy("MONTH(tanggal)")
                ->orderBy("MONTH(tanggal)")
                ->get()->getResultArray();
        }

        // Format ulang data biar urut dan rapi
        $labels = [];
        $penjualan = [];
        $keuntungan = [];
        $pengeluaran = [];

        if ($type === 'month') {
            $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            for ($i = 1; $i <= $days; $i++) {
                $labels[] = "Tgl " . $i;
                $penjualan[] = (int) ($this->findValue($penjualanData, $i, 'total'));
                $keuntungan[] = (int) ($this->findValue($penjualanData, $i, 'keuntungan'));
                $pengeluaran[] = (int) ($this->findValue($pengeluaranData, $i, 'jumlah'));
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = date('M', mktime(0, 0, 0, $i, 10)); // Jan, Feb, dst
                $penjualan[] = (int) ($this->findValue($penjualanData, $i, 'total'));
                $keuntungan[] = (int) ($this->findValue($penjualanData, $i, 'keuntungan'));
                $pengeluaran[] = (int) ($this->findValue($pengeluaranData, $i, 'jumlah'));
            }
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'series' => [
                ['name' => 'Penjualan', 'data' => $penjualan],
                ['name' => 'Pengeluaran', 'data' => $pengeluaran],
                ['name' => 'Keuntungan', 'data' => $keuntungan],
            ]
        ]);
    }

    private function findValue($data, $label, $field)
    {
        foreach ($data as $row) {
            if ((int) $row['label'] === $label) {
                return $row[$field];
            }
        }
        return 0;
    }
}
