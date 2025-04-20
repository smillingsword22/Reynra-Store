<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\ProdukModel;

class Produk extends BaseController
{
    protected $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $data['produk'] = $this->produkModel->findAll();
        return view('master/produk/index', $data);
    }

    public function create()
    {
        return view('produk/create');
    }

    public function store()
    {
        $this->produkModel->save([
            'nama_produk' => $this->request->getPost('nama_produk'),
            'kategori'    => $this->request->getPost('kategori'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'harga_jual'  => $this->request->getPost('harga_jual'),
            'stok'        => $this->request->getPost('stok'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
        ]);

        session()->setFlashdata('success', 'Produk berhasil ditambahkan!');
        return redirect()->to('master/produk')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['produk'] = $this->produkModel->find($id);
        return view('produk/edit', $data);
    }

    public function update($id)
    {
        $produkModel = new ProdukModel();
        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'harga_jual'  => $this->request->getPost('harga_jual'),
        ];
        $produkModel->update($id, $data);

        session()->setFlashdata('success', 'Produk berhasil diperbarui!');
        return redirect()->to('/master/produk');
    }

    public function delete($id)
    {
        $produkModel = new ProdukModel();
        $produkModel->delete($id);

        session()->setFlashdata('success', 'Produk berhasil dihapus!');
        return redirect()->to('/master/produk');
    }
}
