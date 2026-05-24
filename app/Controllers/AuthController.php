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
                'id_user'   => $user['id_user'],
                'nama'      => $user['nama'],
                'username'  => $user['username'],
                'role'      => $user['role']
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

        return redirect()->back()->with('failed', 'Login gagal, username atau password salah.'); //jika salah gagal
    }

    /**
     * Tampilkan form register
     */
    public function showRegister()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('auth/v_register');
    }

    /**
     * Proses register customer baru
     */
    public function register()
    {
        $model = new UserModel();

        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'telepon'  => 'required|numeric|min_length[10]',
            'password' => 'required|min_length[6]',
        ];

        $messages = [
            'nama'     => ['required' => 'Nama lengkap wajib diisi.'],
            'username' => [
                'required'  => 'Username wajib diisi.',
                'min_length'=> 'Username minimal 3 karakter.',
                'is_unique' => 'Username sudah digunakan, pilih yang lain.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar.',
            ],
            'telepon' => [
                'required'   => 'Nomor telepon wajib diisi.',
                'numeric'    => 'Nomor telepon harus berupa angka.',
                'min_length' => 'Nomor telepon minimal 10 digit.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 6 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'telepon'  => $this->request->getPost('telepon'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'customer',
            'status'   => 'aktif',
        ]);

        session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
        return redirect()->to('/login');
    }

    public function logout()   //logout, kembali ke form login
    {
        session()->destroy(); //hapus session
        return redirect()->to('/login');
    }
}