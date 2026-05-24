<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\UserModel;
use App\Models\ServiceModel;
use App\Models\ScheduleModel;
use App\Models\StaffModel;

class BookingController extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        $data['bookings'] = $this->bookingModel
            ->select('bookings.*, users.nama, services.nama as nama_service, schedules.tanggal, staff_user.nama as nama_staff')
            ->join('users', 'users.id_user = bookings.id_user')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->join('staffs', 'staffs.id_staff = bookings.id_staff')
            ->join('users as staff_user', 'staff_user.id_user = staffs.id_user')
            ->orderBy('bookings.id_booking', 'DESC')
            ->findAll();

        return view('admin/bookings/index', $data);
    }

    public function create()
    {
        $staffModel = new StaffModel();

        $data = [
            'users'      => (new UserModel())->where('role', 'customer')->findAll(),
            'services'   => (new ServiceModel())->where('status', 'aktif')->findAll(),
            'schedules'  => (new ScheduleModel())->where('status', 'available')->findAll(),
            'staffs'     => $staffModel->getStaffWithUser(),
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/bookings/create', $data);
    }

    public function store()
    {
        if (!$this->validate($this->bookingModel->getValidationRules(), $this->bookingModel->getValidationMessages())) {
            return redirect()->to('/admin/bookings/create')->withInput();
        }

        $this->bookingModel->save([
            'id_user'           => $this->request->getPost('id_user'),
            'id_staff'          => $this->request->getPost('id_staff'),
            'id_service'        => $this->request->getPost('id_service'),
            'id_schedule'       => $this->request->getPost('id_schedule'),
            'kode_booking'      => 'BOOK-' . strtoupper(substr(md5(uniqid()), 0, 6)),
            'tanggal_booking'   => $this->request->getPost('tanggal_booking'),
            'catatan'           => $this->request->getPost('catatan'),
            'status_booking'    => 'pending',
            'status_pembayaran' => 'belum_bayar',
        ]);

        session()->setFlashdata('success', 'Booking berhasil ditambahkan.');
        return redirect()->to('/admin/bookings');
    }

    public function confirm($id)
    {
        $booking = $this->bookingModel->find($id);

        if ($booking) {
            $this->bookingModel->update($id, [
                'status_booking' => 'dikonfirmasi'
            ]);
            session()->setFlashdata('success', 'Booking berhasil dikonfirmasi.');
        } else {
            session()->setFlashdata('error', 'Booking tidak ditemukan.');
        }

        return redirect()->to('/admin/bookings');
    }

    public function cancel($id)
    {
        $booking = $this->bookingModel->find($id);

        if ($booking) {
            $this->bookingModel->update($id, [
                'status_booking' => 'batal'
            ]);
            session()->setFlashdata('success', 'Booking berhasil dibatalkan/ditolak.');
        } else {
            session()->setFlashdata('error', 'Booking tidak ditemukan.');
        }

        return redirect()->to('/admin/bookings');
    }

    public function delete($id)
    {
        $booking = $this->bookingModel->find($id);

        if ($booking) {
            $this->bookingModel->delete($id);
            session()->setFlashdata('success', 'Booking berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Booking tidak ditemukan.');
        }

        return redirect()->to('/admin/bookings');
    }
}