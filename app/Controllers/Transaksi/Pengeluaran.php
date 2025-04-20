<?php

namespace App\Controllers\Transaksi;

use App\Controllers\BaseController;
use App\Models\PengeluaranModel;

class Pengeluaran extends BaseController
{
    protected $pengeluaranModel;

    public function __construct()
    {
        $this->pengeluaranModel = new PengeluaranModel();
    }

    public function index()
    {
        $data['pengeluaran'] = $this->pengeluaranModel->findAll();
        return view('transaksi/pengeluaran/index', $data);
    }

    public function store()
    {
        $tanggal = $this->request->getPost('tanggal');
        $kategori = $this->request->getPost('kategori');
        $jumlah = $this->request->getPost('jumlah');
        $keterangan = $this->request->getPost('keterangan');

        $data = [
            'tanggal' => $tanggal,
            'kategori' => $kategori,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan
        ];

        $this->pengeluaranModel->insert($data);

        return redirect()->back()->with('success', 'Pengeluaran berhasil disimpan.');
    }
}
