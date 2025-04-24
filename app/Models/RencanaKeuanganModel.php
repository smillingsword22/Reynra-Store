<?php

namespace App\Models;

use CodeIgniter\Model;

class RencanaKeuanganModel extends Model
{
    protected $table      = 'rencana_keuangan';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'kategori_id',
        'target',
        'realisasi',
        'bulan',
        'tahun',
        'keterangan'
    ];

    // Validation rules
    protected $validationRules = [
        'kategori_id' => 'required|is_not_unique[kategori_transaksi.id]',
        'target'      => 'required|decimal',
        'realisasi'   => 'decimal',
        'bulan'       => 'required|integer|greater_than[0]|less_than[13]',
        'tahun'       => 'required|integer',
    ];

    // Validation messages
    protected $validationMessages = [
        'kategori_id' => [
            'required' => 'Kategori tidak boleh kosong.',
            'is_not_unique' => 'Kategori tidak ditemukan.'
        ],
        'target' => [
            'required' => 'Target tidak boleh kosong.',
            'decimal' => 'Target harus berupa angka desimal.'
        ],
        'bulan' => [
            'required' => 'Bulan tidak boleh kosong.',
            'integer' => 'Bulan harus berupa angka.',
            'greater_than' => 'Bulan harus lebih besar dari 0.',
            'less_than' => 'Bulan harus kurang dari 13.'
        ],
        'tahun' => [
            'required' => 'Tahun tidak boleh kosong.',
            'integer' => 'Tahun harus berupa angka.'
        ]
    ];

    // To get kategori from kategori_transaksi table
    public function getKategori()
    {
        return $this->db->table('kategori_transaksi')
            ->select('id, nama')
            ->get()->getResultArray();
    }
}
