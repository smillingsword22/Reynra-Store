<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriTransaksiModel extends Model
{
    protected $table      = 'kategori_transaksi';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'tipe'];

    // Optional: Untuk mengaktifkan timestamp otomatis
    protected $useTimestamps = false;  // Kalau pakai created_at, updated_at, bisa disesuaikan

    // Optional: Custom validation rules
    protected $validationRules = [
        'nama' => 'required|min_length[3]|max_length[100]',
        'tipe' => 'required|in_list[masuk,keluar]'
    ];
}
