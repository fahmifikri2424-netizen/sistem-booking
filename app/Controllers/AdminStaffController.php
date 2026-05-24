<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\StaffModel;

class AdminStaffController extends BaseController
{
    protected $userModel;
    protected $staffModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->staffModel = new StaffModel();
    }

    public function index()
    {
        $data['staffs'] = $this->staffModel
            ->select('staffs.*, users.nama, users.username, users.email, users.telepon, users.status')
            ->join('users', 'users.id_user = staffs.id_user')
            ->orderBy('staffs.id_staff', 'DESC')
            ->findAll();

        return view('admin/staff/index', $data);
    }

    public function create()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];
        return view('admin/staff/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama'         => 'required',
            'username'     => 'required|is_unique[users.username]|min_length[3]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required|min_length[3]',
            'telepon'      => 'required|numeric|min_length[10]',
            'spesialisasi' => 'required',
        ];

        $messages = [
            'nama' => [
                'required' => 'Nama staff wajib diisi.'
            ],
            'username' => [
                'required'  => 'Username wajib diisi.',
                'is_unique' => 'Username ini sudah digunakan.',
                'min_length'=> 'Username minimal terdiri dari 3 karakter.'
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email ini sudah digunakan.'
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal terdiri dari 3 karakter.'
            ],
            'telepon' => [
                'required'   => 'Nomor telepon wajib diisi.',
                'numeric'    => 'Nomor telepon harus berupa angka.',
                'min_length' => 'Nomor telepon minimal terdiri dari 10 digit.'
            ],
            'spesialisasi' => [
                'required' => 'Spesialisasi wajib diisi.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to('/admin/staffs/create')->withInput();
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Simpan ke tabel users
            $this->userModel->save([
                'nama'     => $this->request->getPost('nama'),
                'username' => $this->request->getPost('username'),
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'telepon'  => $this->request->getPost('telepon'),
                'role'     => 'staff',
                'status'   => 'aktif',
            ]);

            $id_user = $this->userModel->getInsertID();

            // 2. Simpan ke tabel staffs
            $this->staffModel->save([
                'id_user'      => $id_user,
                'spesialisasi' => $this->request->getPost('spesialisasi'),
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                session()->setFlashdata('error', 'Gagal menambahkan data staff.');
                return redirect()->to('/admin/staffs/create')->withInput();
            } else {
                $db->transCommit();
                session()->setFlashdata('success', 'Data staff berhasil ditambahkan.');
                return redirect()->to('/admin/staffs');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->to('/admin/staffs/create')->withInput();
        }
    }

    public function edit($id)
    {
        $staff = $this->staffModel
            ->select('staffs.*, users.nama, users.username, users.email, users.telepon, users.status')
            ->join('users', 'users.id_user = staffs.id_user')
            ->find($id);

        if (empty($staff)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data staff tidak ditemukan');
        }

        $data = [
            'staff'      => $staff,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/staff/edit', $data);
    }

    public function update($id)
    {
        $staff = $this->staffModel->find($id);
        if (empty($staff)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data staff tidak ditemukan');
        }

        $id_user = $staff['id_user'];

        $rules = [
            'nama'         => 'required',
            'username'     => "required|min_length[3]|is_unique[users.username,id_user,{$id_user}]",
            'email'        => "required|valid_email|is_unique[users.email,id_user,{$id_user}]",
            'password'     => 'permit_empty|min_length[3]',
            'telepon'      => 'required|numeric|min_length[10]',
            'status'       => 'required',
            'spesialisasi' => 'required',
        ];

        $messages = [
            'nama' => [
                'required' => 'Nama staff wajib diisi.'
            ],
            'username' => [
                'required'  => 'Username wajib diisi.',
                'is_unique' => 'Username ini sudah digunakan oleh akun lain.',
                'min_length'=> 'Username minimal terdiri dari 3 karakter.'
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email ini sudah digunakan oleh akun lain.'
            ],
            'password' => [
                'min_length' => 'Password baru minimal terdiri dari 3 karakter.'
            ],
            'telepon' => [
                'required'   => 'Nomor telepon wajib diisi.',
                'numeric'    => 'Nomor telepon harus berupa angka.',
                'min_length' => 'Nomor telepon minimal terdiri dari 10 digit.'
            ],
            'status' => [
                'required' => 'Status wajib diisi.'
            ],
            'spesialisasi' => [
                'required' => 'Spesialisasi wajib diisi.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to('/admin/staffs/edit/' . $id)->withInput();
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Update data user
            $userData = [
                'nama'     => $this->request->getPost('nama'),
                'username' => $this->request->getPost('username'),
                'email'    => $this->request->getPost('email'),
                'telepon'  => $this->request->getPost('telepon'),
                'status'   => $this->request->getPost('status'),
            ];

            // Update password jika diisi
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $this->userModel->update($id_user, $userData);

            // 2. Update data staff
            $this->staffModel->update($id, [
                'spesialisasi' => $this->request->getPost('spesialisasi')
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                session()->setFlashdata('error', 'Gagal memperbarui data staff.');
                return redirect()->to('/admin/staffs/edit/' . $id)->withInput();
            } else {
                $db->transCommit();
                session()->setFlashdata('success', 'Data staff berhasil diperbarui.');
                return redirect()->to('/admin/staffs');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->to('/admin/staffs/edit/' . $id)->withInput();
        }
    }

    public function delete($id)
    {
        $staff = $this->staffModel->find($id);

        if ($staff) {
            // Karena relasi foreign key ON DELETE CASCADE, menghapus user otomatis akan menghapus staff terkait secara aman.
            $this->userModel->delete($staff['id_user']);
            session()->setFlashdata('success', 'Data staff berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Data staff gagal dihapus (tidak ditemukan).');
        }

        return redirect()->to('/admin/staffs');
    }
}
