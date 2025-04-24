<?php

namespace App\Controllers\Pribadi;

use App\Controllers\BaseController;
use App\Models\RencanaKeuanganModel;
use App\Models\KategoriTransaksiModel;

class Rencana extends BaseController
{
    protected $rencanaKeuanganModel;
    protected $kategoriTransaksiModel;

    public function __construct()
    {
        $this->rencanaKeuanganModel = new RencanaKeuanganModel();
        $this->kategoriTransaksiModel = new KategoriTransaksiModel();
    }

    // Display the Rencana Keuangan list
    public function index()
    {
        $rencanaKeuangan = $this->rencanaKeuanganModel->select('rencana_keuangan.*, kategori_transaksi.nama AS kategori_nama')
            ->join('kategori_transaksi', 'kategori_transaksi.id = rencana_keuangan.kategori_id')
            ->findAll();

        $kategori = $this->kategoriTransaksiModel->findAll();

        return view('pribadi/rencana/index', [
            'rencanaKeuangan' => $rencanaKeuangan,
            'kategori' => $kategori
        ]);
    }

    // Store a new Rencana Keuangan
    public function store()
    {
        $validation = \Config\Services::validation();

        if (!$this->validate([
            'kategori_id' => 'required|is_not_unique[kategori_transaksi.id]',
            'target' => 'required|decimal',
            'realisasi' => 'decimal',
            'bulan' => 'required|integer|greater_than[0]|less_than[13]',
            'tahun' => 'required|integer',
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'kategori_id' => $this->request->getPost('kategori_id'),
            'target' => $this->request->getPost('target'),
            'realisasi' => $this->request->getPost('realisasi'),
            'bulan' => $this->request->getPost('bulan'),
            'tahun' => $this->request->getPost('tahun'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->rencanaKeuanganModel->save($data);

        return redirect()->to('/pribadi/rencana')->with('message', 'Rencana Keuangan berhasil ditambahkan!');
    }

    // Edit an existing Rencana Keuangan
    public function edit($id)
    {
        $rencana = $this->rencanaKeuanganModel->find($id);

        if ($rencana) {
            return $this->response->setJSON($rencana);
        }

        return redirect()->to('/pribadi/rencana')->with('error', 'Rencana tidak ditemukan');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $data = [
            'kategori_id' => $this->request->getPost('kategori_id'),
            'target'      => $this->request->getPost('target'),
            'realisasi'   => $this->request->getPost('realisasi'),
            'bulan'       => $this->request->getPost('bulan'),
            'tahun'       => $this->request->getPost('tahun'),
            'keterangan'  => $this->request->getPost('keterangan'),
        ];

        // Validasi data
        if (!$this->validate([
            'kategori_id' => 'required',
            'target'      => 'required|decimal',
            'realisasi'   => 'required|decimal',
            'bulan'       => 'required',
            'tahun'       => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update data rencana keuangan
        $this->rencanaKeuanganModel->update($id, $data);

        return redirect()->to('/rencana')->with('success', 'Rencana Keuangan berhasil diperbarui');
    }

    // Delete a Rencana Keuangan
    public function delete($id)
    {
        $this->rencanaKeuanganModel->delete($id);
        return redirect()->to('/pribadi/rencana')->with('message', 'Rencana Keuangan berhasil dihapus!');
    }
}
