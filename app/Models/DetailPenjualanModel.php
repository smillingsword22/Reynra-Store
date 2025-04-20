<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPenjualanModel extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['penjualan_id', 'produk_id', 'harga', 'jumlah', 'subtotal'];
}
