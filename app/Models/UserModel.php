<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // Nama tabel di database kamu
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'password', 'name']; // Tambah sesuai field kamu

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}
