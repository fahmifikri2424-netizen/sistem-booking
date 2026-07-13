<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\StaffModel;
use App\Models\UserModel;

class Staff extends BaseController
{
    protected $bookingModel;
    protected $staffModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->staffModel   = new StaffModel();
    }

    /**
     * Ambil id_staff berdasarkan id_user dari session
     */
    private function getIdStaff()
    {
        $id_user = session()->get('id_user');
        $staff   = $this->staffModel->where('id_user', $id_user)->first();
        return $staff ? $staff['id_staff'] : null;
    }

    /**
     * Dashboard staff
     */
    public function index()
    {
        $id_staff = $this->getIdStaff();

        $today = date('Y-m-d');

        // Statistik ringkas untuk dashboard
        $totalHariIni = $this->bookingModel
            ->where('id_staff', $id_staff)
            ->where('tanggal_booking', $today)
            ->countAllResults();

        $totalSelesai = $this->bookingModel
            ->where('id_staff', $id_staff)
            ->where('status_booking', 'selesai')
            ->countAllResults();

        $totalDikonfirmasi = $this->bookingModel
            ->where('id_staff', $id_staff)
            ->where('status_booking', 'dikonfirmasi')
            ->countAllResults();

        $totalPending = $this->bookingModel
            ->where('id_staff', $id_staff)
            ->where('status_booking', 'pending')
            ->countAllResults();

        // Jadwal hari ini (5 terdekat)
        $jadwalHariIni = $this->bookingModel
            ->select('bookings.*, users.nama as nama_customer, services.nama as nama_service, schedules.jam_mulai, schedules.jam_selesai')
            ->join('users', 'users.id_user = bookings.id_user')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->where('bookings.id_staff', $id_staff)
            ->where('bookings.tanggal_booking', $today)
            ->whereIn('bookings.status_booking', ['pending', 'dikonfirmasi'])
            ->orderBy('schedules.jam_mulai', 'ASC')
            ->limit(5)
            ->findAll();

        return view('staff/dashboard', [
            'totalHariIni'      => $totalHariIni,
            'totalSelesai'      => $totalSelesai,
            'totalDikonfirmasi' => $totalDikonfirmasi,
            'totalPending'      => $totalPending,
            'jadwalHariIni'     => $jadwalHariIni,
        ]);
    }

    /**
     * Halaman jadwal tugas harian
     */
    public function jadwal()
    {
        $id_staff = $this->getIdStaff();

        $tanggal = $this->request->getGet('tanggal');

        $query = $this->bookingModel
            ->select('bookings.*, users.nama as nama_customer, users.telepon,
                      services.nama as nama_service, services.harga,
                      schedules.jam_mulai, schedules.jam_selesai')
            ->join('users', 'users.id_user = bookings.id_user')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->where('bookings.id_staff', $id_staff);

        if (!empty($tanggal)) {
            $query->where('bookings.tanggal_booking', $tanggal);
        } else {
            $query->where('bookings.tanggal_booking >=', date('Y-m-d'));
        }

        $jadwal = $query->orderBy('bookings.tanggal_booking', 'ASC')
            ->orderBy('schedules.jam_mulai', 'ASC')
            ->findAll();

        return view('staff/jadwal', [
            'jadwal'  => $jadwal,
            'tanggal' => $tanggal ?? '',
        ]);
    }

    /**
     * Halaman update status pengerjaan
     */
    public function updateStatus()
    {
        $id_staff = $this->getIdStaff();

        // Tampilkan booking yang statusnya pending atau dikonfirmasi (yang masih bisa diupdate)
        $bookings = $this->bookingModel
            ->select('bookings.*, users.nama as nama_customer, users.telepon,
                      services.nama as nama_service,
                      schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai')
            ->join('users', 'users.id_user = bookings.id_user')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->where('bookings.id_staff', $id_staff)
            ->whereIn('bookings.status_booking', ['pending', 'dikonfirmasi'])
            ->orderBy('bookings.tanggal_booking', 'ASC')
            ->orderBy('schedules.jam_mulai', 'ASC')
            ->findAll();

        return view('staff/update_status', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Proses update status booking
     */
    public function prosesUpdateStatus($id)
    {
        $id_staff = $this->getIdStaff();

        // Pastikan booking ini milik staff yang login
        $booking = $this->bookingModel->find($id);

        if (!$booking || $booking['id_staff'] != $id_staff) {
            session()->setFlashdata('error', 'Booking tidak ditemukan atau bukan milik Anda.');
            return redirect()->to('/staff/update-status');
        }

        $status_baru = $this->request->getPost('status_booking');
        $statusValid = ['dikonfirmasi', 'selesai', 'batal'];

        if (!in_array($status_baru, $statusValid)) {
            session()->setFlashdata('error', 'Status tidak valid.');
            return redirect()->to('/staff/update-status');
        }

        $this->bookingModel->update($id, [
            'status_booking' => $status_baru,
        ]);

        session()->setFlashdata('success', 'Status booking berhasil diperbarui menjadi "' . ucfirst($status_baru) . '".');
        return redirect()->to('/staff/update-status');
    }

    /**
     * Halaman riwayat pekerjaan pribadi
     */
    public function riwayat()
    {
        $id_staff = $this->getIdStaff();

        // Filter status (default: semua)
        $filterStatus = $this->request->getGet('status') ?? '';

        $query = $this->bookingModel
            ->select('bookings.*, users.nama as nama_customer, users.telepon,
                      services.nama as nama_service, services.harga,
                      schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai,
                      reviews.rating, reviews.komentar')
            ->join('users', 'users.id_user = bookings.id_user')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->join('reviews', 'reviews.id_booking = bookings.id_booking', 'left')
            ->where('bookings.id_staff', $id_staff)
            ->orderBy('bookings.tanggal_booking', 'DESC')
            ->orderBy('schedules.jam_mulai', 'DESC');

        if (!empty($filterStatus)) {
            $query->where('bookings.status_booking', $filterStatus);
        }

        $riwayat = $query->findAll();

        return view('staff/riwayat', [
            'riwayat'       => $riwayat,
            'filterStatus'  => $filterStatus,
        ]);
    }
}
