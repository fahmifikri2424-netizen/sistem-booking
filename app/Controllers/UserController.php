<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // TAMPIL DATA USER
    public function index()
    {
        $data['users'] = $this->userModel
            ->where('role', 'customer')
            ->findAll();

        return view('admin/user/index', $data);
    }

    // FORM TAMBAH USER
    public function create()
    {
        return view('admin/user/create');
    }

    // SIMPAN USER
    public function store()
    {
        $this->userModel->save([

            'username' => $this->request->getPost('username'),

            'nama' => $this->request->getPost('nama'),

            'email' => $this->request->getPost('email'),

            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),

            'telepon' => $this->request->getPost('telepon'),

            'role' => 'customer',

            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('/admin/users');
    }

    // FORM EDIT
    public function edit($id)
    {
        $data['user'] = $this->userModel->find($id);

        return view('admin/user/edit', $data);
    }

    // UPDATE USER
    public function update($id)
    {
        $data = [

            'username' => $this->request->getPost('username'),

            'nama' => $this->request->getPost('nama'),

            'email' => $this->request->getPost('email'),

            'telepon' => $this->request->getPost('telepon'),

            'status' => $this->request->getPost('status')
        ];

        // jika password diisi
        if($this->request->getPost('password') != '')
        {
            $data['password'] = password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            );
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/admin/users');
    }

    // DELETE USER
    public function delete($id)
    {
        $this->userModel->delete($id);

        return redirect()->to('/admin/users');
    }
}