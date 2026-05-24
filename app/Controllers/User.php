<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ServiceModel;
use App\Models\ScheduleModel;
use App\Models\StaffModel;
use App\Models\ReviewModel;
use App\Models\UserModel;

class User extends BaseController
{
    protected $bookingModel;
    protected $serviceModel;
    protected $scheduleModel;
    protected $staffModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->bookingModel  = new BookingModel();
        $this->serviceModel  = new ServiceModel();
        $this->scheduleModel = new ScheduleModel();
        $this->staffModel    = new StaffModel();
        $this->reviewModel   = new ReviewModel();
    }

    /** Helper: ambil id_user dari session */
    private function getUserId()
    {
        return session()->get('id_user');
    }

    /**
     * Dashboard user
     */
    public function index()
    {
        $id_user = $this->getUserId();

        $totalBooking  = $this->bookingModel->where('id_user', $id_user)->countAllResults();
        $totalSelesai  = $this->bookingModel->where('id_user', $id_user)->where('status_booking', 'selesai')->countAllResults();
        $totalPending  = $this->bookingModel->where('id_user', $id_user)->where('status_booking', 'pending')->countAllResults();
        $totalDikonfirmasi = $this->bookingModel->where('id_user', $id_user)->where('status_booking', 'dikonfirmasi')->countAllResults();

        // Booking terbaru (3 terakhir)
        $bookingTerbaru = $this->bookingModel
            ->select('bookings.*, services.nama as nama_service, schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai, staff_user.nama as nama_staff')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->join('staffs', 'staffs.id_staff = bookings.id_staff')
            ->join('users as staff_user', 'staff_user.id_user = staffs.id_user')
            ->where('bookings.id_user', $id_user)
            ->orderBy('bookings.id_booking', 'DESC')
            ->limit(3)
            ->findAll();

        return view('user/dashboard', [
            'totalBooking'      => $totalBooking,
            'totalSelesai'      => $totalSelesai,
            'totalPending'      => $totalPending,
            'totalDikonfirmasi' => $totalDikonfirmasi,
            'bookingTerbaru'    => $bookingTerbaru,
        ]);
    }

    /**
     * Halaman daftar layanan (browse)
     */
    public function layanan()
    {
        $layanan = $this->serviceModel->where('status', 'aktif')->findAll();

        // Calculate average rating for each service
        $db = \Config\Database::connect();
        foreach ($layanan as &$item) {
            $query = $db->query("
                SELECT AVG(r.rating) as avg_rating, COUNT(r.id_review) as total_reviews 
                FROM reviews r 
                JOIN bookings b ON b.id_booking = r.id_booking 
                WHERE b.id_service = ?
            ", [$item['id_service']]);
            $result = $query->getRow();
            $item['avg_rating'] = $result->avg_rating ? round($result->avg_rating, 1) : 0;
            $item['total_reviews'] = $result->total_reviews;
        }

        return view('user/layanan', [
            'layanan' => $layanan,
        ]);
    }

    /**
     * Halaman pilih jadwal untuk layanan tertentu
     */
    public function pilihJadwal($id_service)
    {
        $service = $this->serviceModel->find($id_service);

        if (!$service || $service['status'] != 'aktif') {
            return redirect()->to('/user/layanan')->with('error', 'Layanan tidak ditemukan.');
        }

        // Slot waktu yang masih tersedia (tanggal >= hari ini)
        $schedules = $this->scheduleModel
            ->where('status', 'available')
            ->where('tanggal >=', date('Y-m-d'))
            ->orderBy('tanggal', 'ASC')
            ->orderBy('jam_mulai', 'ASC')
            ->findAll();

        // Staff aktif
        $staffs = $this->staffModel->getStaffWithUser();

        return view('user/pilih_jadwal', [
            'service'   => $service,
            'schedules' => $schedules,
            'staffs'    => $staffs,
        ]);
    }

    /**
     * Halaman form booking (isi catatan)
     */
    public function formBooking()
    {
        $id_service  = $this->request->getGet('id_service');
        $id_schedule = $this->request->getGet('id_schedule');
        $id_staff    = $this->request->getGet('id_staff');

        $service  = $this->serviceModel->find($id_service);
        $schedule = $this->scheduleModel->find($id_schedule);

        if (!$service || !$schedule) {
            return redirect()->to('/user/layanan')->with('error', 'Data tidak valid.');
        }

        // Cari nama staff
        $staffData = $this->staffModel
            ->select('staffs.*, users.nama')
            ->join('users', 'users.id_user = staffs.id_user')
            ->find($id_staff);

        return view('user/form_booking', [
            'service'    => $service,
            'schedule'   => $schedule,
            'staffData'  => $staffData,
            'validation' => \Config\Services::validation(),
        ]);
    }

    /**
     * Proses simpan booking
     */
    public function storeBooking()
    {
        $id_user     = $this->getUserId();
        $id_service  = $this->request->getPost('id_service');
        $id_schedule = $this->request->getPost('id_schedule');
        $id_staff    = $this->request->getPost('id_staff');
        $catatan     = $this->request->getPost('catatan');

        $schedule = $this->scheduleModel->find($id_schedule);

        if (!$schedule || $schedule['status'] != 'available') {
            session()->setFlashdata('error', 'Slot waktu sudah tidak tersedia. Silakan pilih yang lain.');
            return redirect()->to('/user/layanan');
        }

        $this->bookingModel->save([
            'id_user'           => $id_user,
            'id_staff'          => $id_staff,
            'id_service'        => $id_service,
            'id_schedule'       => $id_schedule,
            'kode_booking'      => 'BOOK-' . strtoupper(substr(md5(uniqid()), 0, 6)),
            'tanggal_booking'   => $schedule['tanggal'],
            'catatan'           => $catatan,
            'status_booking'    => 'pending',
            'status_pembayaran' => 'belum_bayar',
        ]);

        session()->setFlashdata('success', 'Booking berhasil dibuat! Mohon tunggu konfirmasi dari tim kami.');
        return redirect()->to('/user/riwayat');
    }

    /**
     * Halaman riwayat booking & status pembayaran
     */
    public function riwayat()
    {
        $id_user      = $this->getUserId();
        $filterStatus = $this->request->getGet('status') ?? '';

        $query = $this->bookingModel
            ->select('bookings.*, services.nama as nama_service, services.harga,
                      schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai,
                      staff_user.nama as nama_staff,
                      reviews.id_review, reviews.rating, reviews.komentar')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->join('staffs', 'staffs.id_staff = bookings.id_staff')
            ->join('users as staff_user', 'staff_user.id_user = staffs.id_user')
            ->join('reviews', 'reviews.id_booking = bookings.id_booking', 'left')
            ->where('bookings.id_user', $id_user)
            ->orderBy('bookings.id_booking', 'DESC');

        if (!empty($filterStatus)) {
            $query->where('bookings.status_booking', $filterStatus);
        }

        $riwayat = $query->findAll();

        return view('user/riwayat', [
            'riwayat'      => $riwayat,
            'filterStatus' => $filterStatus,
        ]);
    }

    /**
     * Batalkan booking (hanya jika status masih pending)
     */
    public function batalBooking($id)
    {
        $id_user = $this->getUserId();
        $booking = $this->bookingModel->find($id);

        if (!$booking || $booking['id_user'] != $id_user) {
            session()->setFlashdata('error', 'Booking tidak ditemukan.');
            return redirect()->to('/user/riwayat');
        }

        if ($booking['status_booking'] != 'pending') {
            session()->setFlashdata('error', 'Booking hanya bisa dibatalkan jika masih berstatus Pending.');
            return redirect()->to('/user/riwayat');
        }

        $this->bookingModel->update($id, ['status_booking' => 'batal']);
        session()->setFlashdata('success', 'Booking berhasil dibatalkan.');
        return redirect()->to('/user/riwayat');
    }

    /**
     * Halaman beri ulasan
     */
    public function formUlasan($id_booking)
    {
        $id_user = $this->getUserId();
        $booking = $this->bookingModel
            ->select('bookings.*, services.nama as nama_service, schedules.tanggal,
                      schedules.jam_mulai, schedules.jam_selesai, staff_user.nama as nama_staff')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->join('staffs', 'staffs.id_staff = bookings.id_staff')
            ->join('users as staff_user', 'staff_user.id_user = staffs.id_user')
            ->where('bookings.id_booking', $id_booking)
            ->where('bookings.id_user', $id_user)
            ->first();

        if (!$booking) {
            session()->setFlashdata('error', 'Booking tidak ditemukan.');
            return redirect()->to('/user/riwayat');
        }

        if ($booking['status_booking'] != 'selesai') {
            session()->setFlashdata('error', 'Ulasan hanya bisa diberikan untuk booking yang sudah selesai.');
            return redirect()->to('/user/riwayat');
        }

        // Cek apakah sudah pernah memberi ulasan
        $existingReview = $this->reviewModel->where('id_booking', $id_booking)->first();
        if ($existingReview) {
            session()->setFlashdata('error', 'Anda sudah memberikan ulasan untuk booking ini.');
            return redirect()->to('/user/riwayat');
        }

        return view('user/form_ulasan', [
            'booking'    => $booking,
            'validation' => \Config\Services::validation(),
        ]);
    }

    /**
     * Proses simpan ulasan
     */
    public function storeUlasan()
    {
        $id_user     = $this->getUserId();
        $id_booking  = $this->request->getPost('id_booking');
        $rating      = $this->request->getPost('rating');
        $komentar    = $this->request->getPost('komentar');

        // Validasi kepemilikan booking
        $booking = $this->bookingModel->find($id_booking);
        if (!$booking || $booking['id_user'] != $id_user || $booking['status_booking'] != 'selesai') {
            session()->setFlashdata('error', 'Tidak dapat menyimpan ulasan.');
            return redirect()->to('/user/riwayat');
        }

        // Cek duplikasi
        $existing = $this->reviewModel->where('id_booking', $id_booking)->first();
        if ($existing) {
            session()->setFlashdata('error', 'Anda sudah memberikan ulasan untuk booking ini.');
            return redirect()->to('/user/riwayat');
        }

        if (!$this->validate($this->reviewModel->getValidationRules(), $this->reviewModel->getValidationMessages())) {
            return redirect()->back()->withInput();
        }

        $this->reviewModel->save([
            'id_booking' => $id_booking,
            'rating'     => $rating,
            'komentar'   => $komentar,
        ]);

        session()->setFlashdata('success', 'Terima kasih! Ulasan Anda telah berhasil disimpan.');
        return redirect()->to('/user/riwayat');
    }
}
