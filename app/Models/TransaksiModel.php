<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table      = 'transaksi';
    protected $primaryKey = 'id';

    protected $allowedFields = ['tanggal', 'kategori_id', 'tipe', 'jumlah', 'keterangan'];

    // Optional: Untuk mengaktifkan timestamp otomatis
    protected $useTimestamps = true;

    // Relasi dengan kategori_transaksi
    public function getKategoriTransaksi()
    {
        return $this->join('kategori_transaksi', 'kategori_transaksi.id = transaksi.kategori_id')
                    ->select('transaksi.*, kategori_transaksi.nama as kategori_nama');
    }

    // Optional: Custom validation rules
    protected $validationRules = [
        'tanggal'    => 'required|valid_date',
        'kategori_id'=> 'required|is_not_unique[kategori_transaksi.id]',
        'tipe'       => 'required|in_list[masuk,keluar]',
        'jumlah'     => 'required|decimal',
        'keterangan' => 'permit_empty|string'
    ];
}
