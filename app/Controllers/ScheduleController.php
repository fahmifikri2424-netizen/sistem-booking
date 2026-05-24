<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ScheduleModel;

class ScheduleController extends BaseController
{
    protected $scheduleModel;

    public function __construct()
    {
        $this->scheduleModel = new ScheduleModel();
    }

    public function index()
    {
        // Sort by tanggal descending
        $data = [
            'schedules' => $this->scheduleModel->orderBy('tanggal', 'DESC')->findAll()
        ];
        return view('admin/schedule/index', $data);
    }

    public function create()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];
        return view('admin/schedule/create', $data);
    }

    public function store()
    {
        if (!$this->validate($this->scheduleModel->getValidationRules(), $this->scheduleModel->getValidationMessages())) {
            return redirect()->to('/admin/schedules/create')->withInput();
        }

        $this->scheduleModel->save([
            'tanggal'     => $this->request->getPost('tanggal'),
            'jam_mulai'   => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'kapasitas'   => $this->request->getPost('kapasitas'),
            'status'      => $this->request->getPost('status')
        ]);

        session()->setFlashdata('success', 'Data jadwal berhasil ditambahkan.');
        return redirect()->to('/admin/schedules');
    }

    public function edit($id)
    {
        $data = [
            'schedule'   => $this->scheduleModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['schedule'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Jadwal tidak ditemukan');
        }

        return view('admin/schedule/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate($this->scheduleModel->getValidationRules(), $this->scheduleModel->getValidationMessages())) {
            return redirect()->to('/admin/schedules/edit/' . $id)->withInput();
        }

        $schedule = $this->scheduleModel->find($id);
        if (!$schedule) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Jadwal tidak ditemukan');
        }

        $this->scheduleModel->update($id, [
            'tanggal'     => $this->request->getPost('tanggal'),
            'jam_mulai'   => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'kapasitas'   => $this->request->getPost('kapasitas'),
            'status'      => $this->request->getPost('status')
        ]);

        session()->setFlashdata('success', 'Data jadwal berhasil diperbarui.');
        return redirect()->to('/admin/schedules');
    }

    public function delete($id)
    {
        $schedule = $this->scheduleModel->find($id);

        if ($schedule) {
            $this->scheduleModel->delete($id);
            session()->setFlashdata('success', 'Data jadwal berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Data jadwal gagal dihapus (tidak ditemukan).');
        }

        return redirect()->to('/admin/schedules');
    }
}
