<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index(): string
    {
        return view('login_view');
    }

    public function doLogin()
    {
        $session = session();
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->getUserByEmail($email);

        if ($user) {
            // Cek password (pastikan di DB sudah di-hash pakai password_hash)
            if (password_verify($password, $user['password'])) {
                $session->set([
                    'user_id' => $user['id'],
                    'user_name' => $user['name'],
                    'user_email' => $user['email'],
                    'logged_in' => true
                ]);
                return redirect()->to('/dashboard'); // Ganti sesuai halaman setelah login
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/');
            }
        } else {
            $session->setFlashdata('error', 'Email tidak ditemukan.');
            return redirect()->to('/');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
