<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\UserModel;
use App\Models\ServiceModel;

class Admin extends BaseController
{
    public function index()
    {
        $bookingModel = new BookingModel();
        $userModel    = new UserModel();
        $serviceModel = new ServiceModel();
        $db           = \Config\Database::connect();

        // 1. Statistik Dasar
        $totalBooking = $bookingModel->countAllResults();
        
        $totalPelanggan = $userModel->where('role', 'customer')->countAllResults();
        
        $totalLayanan = $serviceModel->where('status', 'aktif')->countAllResults();

        // 2. Pendapatan (Dari booking yang sudah selesai/dibayar)
        // Asumsi: pendapatan didapat dari booking berstatus 'selesai'
        $pendapatanQuery = $db->query("
            SELECT SUM(s.harga) as total_pendapatan 
            FROM bookings b 
            JOIN services s ON b.id_service = s.id_service 
            WHERE b.status_booking = 'selesai'
        ");
        $totalPendapatan = $pendapatanQuery->getRow()->total_pendapatan ?? 0;

        // 3. Status Booking
        $statusPending = $bookingModel->where('status_booking', 'pending')->countAllResults();
        $statusSelesai = $bookingModel->where('status_booking', 'selesai')->countAllResults();
        $statusBatal   = $bookingModel->where('status_booking', 'batal')->countAllResults();

        // 4. Layanan Terpopuler (Top 5)
        $layananPopulerQuery = $db->query("
            SELECT s.nama, s.harga, COUNT(b.id_booking) as total_dipesan
            FROM services s
            LEFT JOIN bookings b ON s.id_service = b.id_service
            GROUP BY s.id_service
            ORDER BY total_dipesan DESC
            LIMIT 5
        ");
        $layananPopuler = $layananPopulerQuery->getResultArray();

        // 5. Booking Terbaru (5 data terakhir)
        $bookingTerbaru = $bookingModel
            ->select('bookings.*, users.nama as nama_customer, services.nama as nama_service, schedules.tanggal')
            ->join('users', 'users.id_user = bookings.id_user')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->orderBy('bookings.id_booking', 'DESC')
            ->limit(5)
            ->findAll();

        return view('admin/dashboard', [
            'totalBooking'    => $totalBooking,
            'totalPelanggan'  => $totalPelanggan,
            'totalLayanan'    => $totalLayanan,
            'totalPendapatan' => $totalPendapatan,
            'statusPending'   => $statusPending,
            'statusSelesai'   => $statusSelesai,
            'statusBatal'     => $statusBatal,
            'layananPopuler'  => $layananPopuler,
            'bookingTerbaru'  => $bookingTerbaru,
        ]);
    }
}
