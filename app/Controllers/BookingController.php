<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\UserModel;
use App\Models\ServiceModel;
use App\Models\ScheduleModel;
use App\Models\StaffModel;
use App\Libraries\GoogleCalendar;

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
            ->select('bookings.*, users.nama, services.nama as nama_service, schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai, staff_user.nama as nama_staff')
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

    /**
     * Mengkonfirmasi booking dan secara otomatis membuat event di Google Calendar.
     */
    public function confirm($id)
    {
        // Ambil data booking lengkap beserta relasi
        $booking = $this->bookingModel
            ->select('bookings.*, users.nama as nama_pelanggan, services.nama as nama_service, schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai, staff_user.nama as nama_staff')
            ->join('users', 'users.id_user = bookings.id_user')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->join('staffs', 'staffs.id_staff = bookings.id_staff')
            ->join('users as staff_user', 'staff_user.id_user = staffs.id_user')
            ->find($id);

        if (!$booking) {
            session()->setFlashdata('error', 'Booking tidak ditemukan.');
            return redirect()->to('/admin/bookings');
        }

        // Update status booking menjadi dikonfirmasi
        $this->bookingModel->update($id, [
            'status_booking' => 'dikonfirmasi'
        ]);

        // Coba buat event di Google Calendar
        $googleEventId = $this->createGoogleCalendarEvent($booking);

        // Simpan ID event Google Calendar ke database (jika berhasil)
        if ($googleEventId) {
            $this->bookingModel->update($id, [
                'google_calendar_event_id' => $googleEventId
            ]);
            session()->setFlashdata('success', 'Booking berhasil dikonfirmasi dan jadwal telah ditambahkan ke Google Calendar. 📅');
        } else {
            session()->setFlashdata('success', 'Booking berhasil dikonfirmasi. (Catatan: Gagal menambahkan ke Google Calendar, cek log untuk detail.)');
        }

        return redirect()->to('/admin/bookings');
    }

    /**
     * Membatalkan booking dan menghapus event dari Google Calendar jika ada.
     */
    public function cancel($id)
    {
        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            session()->setFlashdata('error', 'Booking tidak ditemukan.');
            return redirect()->to('/admin/bookings');
        }

        // Hapus event dari Google Calendar jika ada ID-nya
        if (!empty($booking['google_calendar_event_id'])) {
            $this->deleteGoogleCalendarEvent($booking['google_calendar_event_id']);
        }

        $this->bookingModel->update($id, [
            'status_booking'           => 'batal',
            'google_calendar_event_id' => null,
        ]);

        session()->setFlashdata('success', 'Booking berhasil dibatalkan/ditolak.');
        return redirect()->to('/admin/bookings');
    }

    public function delete($id)
    {
        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            session()->setFlashdata('error', 'Booking tidak ditemukan.');
            return redirect()->to('/admin/bookings');
        }

        // Hapus event dari Google Calendar jika ada
        if (!empty($booking['google_calendar_event_id'])) {
            $this->deleteGoogleCalendarEvent($booking['google_calendar_event_id']);
        }

        $this->bookingModel->delete($id);
        session()->setFlashdata('success', 'Booking berhasil dihapus.');
        return redirect()->to('/admin/bookings');
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPER METHODS
    // -------------------------------------------------------------------------

    /**
     * Membuat event Google Calendar dari data booking.
     * Mengembalikan Event ID jika berhasil, atau null jika gagal.
     */
    private function createGoogleCalendarEvent(array $booking): ?string
    {
        try {
            $googleCalendar = new GoogleCalendar();

            return $googleCalendar->createBookingEvent([
                'kode_booking'   => $booking['kode_booking'],
                'nama_pelanggan' => $booking['nama_pelanggan'],
                'nama_staff'     => $booking['nama_staff'],
                'nama_service'   => $booking['nama_service'],
                'tanggal'        => $booking['tanggal'],
                'jam_mulai'      => $booking['jam_mulai'],
                'jam_selesai'    => $booking['jam_selesai'],
                'catatan'        => $booking['catatan'] ?? '',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[BookingController] Google Calendar error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Menghapus event Google Calendar berdasarkan event ID.
     */
    private function deleteGoogleCalendarEvent(string $eventId): void
    {
        try {
            $googleCalendar = new GoogleCalendar();
            $googleCalendar->deleteEvent($eventId);
        } catch (\Exception $e) {
            log_message('error', '[BookingController] Gagal hapus Google Calendar event: ' . $e->getMessage());
        }
    }
}