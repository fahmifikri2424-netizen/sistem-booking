<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ServiceModel;
use App\Models\BookingModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * ApiController
 *
 * Menyediakan endpoint API publik untuk sistem booking.
 *
 * Endpoints:
 *   GET /api/services           - Daftar semua layanan aktif
 *   GET /api/booking-status/{id} - Status booking berdasarkan ID atau kode_booking
 */
class ApiController extends BaseController
{
    // -------------------------------------------------------------------------
    // HELPER: JSON Response
    // -------------------------------------------------------------------------

    /**
     * Mengembalikan response JSON berhasil (HTTP 200 / custom code).
     */
    private function successResponse(array $data, int $statusCode = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setContentType('application/json')
            ->setJSON([
                'status'  => 'success',
                'code'    => $statusCode,
                'data'    => $data,
            ]);
    }

    /**
     * Mengembalikan response JSON error.
     */
    private function errorResponse(string $message, int $statusCode = 400): ResponseInterface
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setContentType('application/json')
            ->setJSON([
                'status'  => 'error',
                'code'    => $statusCode,
                'message' => $message,
            ]);
    }

    // =========================================================================
    // ENDPOINT 1: GET /api/services
    // =========================================================================

    /**
     * Mengembalikan daftar semua layanan publik (status = aktif).
     *
     * @return ResponseInterface
     */
    public function services(): ResponseInterface
    {
        $serviceModel = new ServiceModel();

        // Ambil hanya layanan yang berstatus aktif
        $services = $serviceModel
            ->select('id_service, nama, deskripsi, harga, durasi, foto, status')
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->findAll();

        // Format data untuk response API
        $formatted = array_map(function ($service) {
            $fotoUrl = null;
            if (!empty($service['foto'])) {
                $baseUrl = rtrim(base_url(), '/');
                $fotoUrl = $baseUrl . '/uploads/services/' . $service['foto'];
            }

            return [
                'id'         => (int) $service['id_service'],
                'nama'       => $service['nama'],
                'deskripsi'  => $service['deskripsi'],
                'harga'      => (float) $service['harga'],
                'durasi'     => (int) $service['durasi'],   // dalam menit
                'foto_url'   => $fotoUrl,
                'status'     => $service['status'],
            ];
        }, $services);

        return $this->successResponse([
            'total'    => count($formatted),
            'services' => $formatted,
        ]);
    }

    // =========================================================================
    // ENDPOINT 2: GET /api/booking-status/{id}
    // =========================================================================

    /**
     * Mengembalikan status booking berdasarkan:
     *   - id numerik (id_booking), ATAU
     *   - kode_booking (contoh: BOOK-AB12CD)
     *
     * @param  string $id  ID atau kode booking
     * @return ResponseInterface
     */
    public function bookingStatus(string $id): ResponseInterface
    {
        $bookingModel = new BookingModel();

        // Tentukan pencarian: numerik → cari by id_booking, lainnya → kode_booking
        if (ctype_digit($id)) {
            $booking = $bookingModel
                ->select('bookings.id_booking, bookings.kode_booking, bookings.tanggal_booking,
                          bookings.catatan, bookings.status_booking, bookings.status_pembayaran,
                          bookings.created_at, bookings.updated_at,
                          users.nama   as nama_pelanggan,
                          services.nama as nama_layanan,
                          services.harga,
                          services.durasi,
                          schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai,
                          staff_user.nama as nama_staff')
                ->join('users', 'users.id_user = bookings.id_user')
                ->join('services', 'services.id_service = bookings.id_service')
                ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
                ->join('staffs', 'staffs.id_staff = bookings.id_staff')
                ->join('users as staff_user', 'staff_user.id_user = staffs.id_user')
                ->where('bookings.id_booking', (int) $id)
                ->first();
        } else {
            $kode = strtoupper(trim($id));
            $booking = $bookingModel
                ->select('bookings.id_booking, bookings.kode_booking, bookings.tanggal_booking,
                          bookings.catatan, bookings.status_booking, bookings.status_pembayaran,
                          bookings.created_at, bookings.updated_at,
                          users.nama   as nama_pelanggan,
                          services.nama as nama_layanan,
                          services.harga,
                          services.durasi,
                          schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai,
                          staff_user.nama as nama_staff')
                ->join('users', 'users.id_user = bookings.id_user')
                ->join('services', 'services.id_service = bookings.id_service')
                ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
                ->join('staffs', 'staffs.id_staff = bookings.id_staff')
                ->join('users as staff_user', 'staff_user.id_user = staffs.id_user')
                ->where('bookings.kode_booking', $kode)
                ->first();
        }

        // Jika tidak ditemukan
        if (!$booking) {
            return $this->errorResponse('Booking tidak ditemukan.', 404);
        }

        // Mapping status ke label yang lebih deskriptif
        $statusLabel = [
            'pending'      => 'Menunggu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'selesai'      => 'Selesai',
            'batal'        => 'Dibatalkan',
        ];

        $pembayaranLabel = [
            'belum_bayar' => 'Belum Dibayar',
            'sudah_bayar' => 'Sudah Dibayar',
        ];

        $result = [
            'id_booking'         => (int) $booking['id_booking'],
            'kode_booking'       => $booking['kode_booking'],
            'nama_pelanggan'     => $booking['nama_pelanggan'],
            'nama_layanan'       => $booking['nama_layanan'],
            'harga'              => (float) $booking['harga'],
            'durasi_menit'       => (int) $booking['durasi'],
            'nama_staff'         => $booking['nama_staff'],
            'tanggal'            => $booking['tanggal'],
            'jam_mulai'          => $booking['jam_mulai'],
            'jam_selesai'        => $booking['jam_selesai'],
            'tanggal_booking'    => $booking['tanggal_booking'],
            'catatan'            => $booking['catatan'],
            'status_booking'     => $booking['status_booking'],
            'status_booking_label'  => $statusLabel[$booking['status_booking']] ?? $booking['status_booking'],
            'status_pembayaran'  => $booking['status_pembayaran'],
            'status_pembayaran_label' => $pembayaranLabel[$booking['status_pembayaran']] ?? $booking['status_pembayaran'],
            'dibuat_pada'        => $booking['created_at'],
            'diperbarui_pada'    => $booking['updated_at'],
        ];

        return $this->successResponse(['booking' => $result]);
    }
}
