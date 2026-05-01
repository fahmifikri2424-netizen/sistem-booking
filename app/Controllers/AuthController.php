<?php

namespace App\Controllers;

use App\Models\UserModel;

//membuat sistem login
//buat authcontroler = php spark make:controller AuthController
//Session adalah cara untuk menyimpan data sementara di server tentang user yang sedang menggunakan aplikasi

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) { //cek status login

            // kalau sudah login, langsung ke dashboard
            if (session()->get('role') == 'admin') {
                return redirect()->to('/admin');
            } elseif (session()->get('role') == 'staff') {
                return redirect()->to('/staff');
            } else {
                return redirect()->to('/user');
            }
        }

        return view('auth/v_login');//menampilkan halaman login
    }

    public function login() //sistem login
    {
        $model = new UserModel();

        $username = $this->request->getPost('username'); //ambil data dari form
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first(); //cek di database

        if ($user && password_verify($password, $user['password'])) { //verifikasi password
            session()->set([                                          //simpan session kalau kondisi diatas benar
                'logged_in' => true,
                'username' => $user['username'],
                'role' => $user['role']
            ]);

            //  redirect berdasarkan role
            if ($user['role'] == 'admin') {
                return redirect()->to('/admin'); //masuk ke admin
            } elseif ($user['role'] == 'staff') {
                return redirect()->to('/staff'); //masuk ke staff
            } else {
                return redirect()->to('/user'); //masuk ke user
            }
        }

        return redirect()->back()->with('failed', 'Login gagal'); //jika salah gagal
    }

    public function logout()   //logout, kembali ke form login
    {
        session()->destroy(); //hapus session
        return redirect()->to('/login');
    }
}