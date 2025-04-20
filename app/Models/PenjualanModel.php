<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_penjualan', 'total', 'metode_pembayaran', 'tanggal', 'keuntungan'];
    protected $useTimestamps = true;
    protected $createdField  = 'tanggal';
    protected $updatedField  = 'tanggal';
}
