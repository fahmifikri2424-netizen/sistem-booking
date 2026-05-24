<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ServiceModel;

class ServiceController extends BaseController
{
    protected $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
    }

    public function index()
    {
        $data = [
            'services' => $this->serviceModel->findAll()
        ];
        return view('admin/service/index', $data);
    }

    public function create()
    {
        // passing validation to view if any
        $data = [
            'validation' => \Config\Services::validation()
        ];
        return view('admin/service/create', $data);
    }

    public function store()
    {
        // Validate input
        $rules = $this->serviceModel->getValidationRules();
        $rules['foto'] = 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]';
        
        $messages = $this->serviceModel->getValidationMessages();
        $messages['foto'] = [
            'max_size' => 'Ukuran gambar maksimal 2MB.',
            'is_image' => 'File yang dipilih bukan gambar.',
            'mime_in'  => 'Format gambar harus jpg, jpeg, atau png.'
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to('/admin/services/create')->withInput();
        }

        // Handle file upload
        $fileFoto = $this->request->getFile('foto');
        $namaFoto = null;

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/services', $namaFoto);
        }

        // Save data
        $this->serviceModel->save([
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga'     => $this->request->getPost('harga'),
            'durasi'    => $this->request->getPost('durasi'),
            'foto'      => $namaFoto,
            'status'    => $this->request->getPost('status'),
        ]);

        session()->setFlashdata('success', 'Data layanan berhasil ditambahkan.');
        return redirect()->to('/admin/services');
    }

    public function edit($id)
    {
        $data = [
            'service'    => $this->serviceModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        
        if (empty($data['service'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Layanan tidak ditemukan');
        }

        return view('admin/service/edit', $data);
    }

    public function update($id)
    {
        // Validate input
        $rules = $this->serviceModel->getValidationRules();
        $rules['foto'] = 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]';
        
        $messages = $this->serviceModel->getValidationMessages();
        $messages['foto'] = [
            'max_size' => 'Ukuran gambar maksimal 2MB.',
            'is_image' => 'File yang dipilih bukan gambar.',
            'mime_in'  => 'Format gambar harus jpg, jpeg, atau png.'
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to('/admin/services/edit/' . $id)->withInput();
        }

        $service = $this->serviceModel->find($id);
        if (!$service) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Layanan tidak ditemukan');
        }

        // Handle file upload
        $fileFoto = $this->request->getFile('foto');
        $namaFoto = $service['foto'];

        // Cek jika ada file gambar yang diupload
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/services', $namaFoto);
            
            // Hapus file lama jika ada
            if ($service['foto'] && file_exists('uploads/services/' . $service['foto'])) {
                unlink('uploads/services/' . $service['foto']);
            }
        }

        // Update data
        $this->serviceModel->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga'     => $this->request->getPost('harga'),
            'durasi'    => $this->request->getPost('durasi'),
            'foto'      => $namaFoto,
            'status'    => $this->request->getPost('status'),
        ]);

        session()->setFlashdata('success', 'Data layanan berhasil diubah.');
        return redirect()->to('/admin/services');
    }

    public function delete($id)
    {
        $service = $this->serviceModel->find($id);

        if ($service) {
            // Hapus file gambar jika ada
            if ($service['foto'] && file_exists('uploads/services/' . $service['foto'])) {
                unlink('uploads/services/' . $service['foto']);
            }
            $this->serviceModel->delete($id);
            session()->setFlashdata('success', 'Data layanan berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Data layanan gagal dihapus (tidak ditemukan).');
        }

        return redirect()->to('/admin/services');
    }
}
