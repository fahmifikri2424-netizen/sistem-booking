<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\BookingModel;

class AdminPaymentController extends BaseController
{
    protected PaymentModel $paymentModel;
    protected BookingModel $bookingModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        // Ambil filter status jika ada
        $filterStatus = $this->request->getGet('status');

        // Build query
        $builder = $this->paymentModel
            ->select('payments.*, bookings.kode_booking, bookings.status_pembayaran as status_booking_bayar,
                      users.nama as nama_pelanggan, services.nama as nama_service')
            ->join('bookings', 'bookings.id_booking = payments.id_booking')
            ->join('users', 'users.id_user = bookings.id_user')
            ->join('services', 'services.id_service = bookings.id_service');

        if (!empty($filterStatus)) {
            $builder->where('payments.status', $filterStatus);
        }

        // Urutkan dari yang terbaru
        $builder->orderBy('payments.id_payment', 'DESC');
        
        $payments = $builder->findAll();

        $data = [
            'title'        => 'Laporan Pembayaran',
            'payments'     => $payments,
            'filterStatus' => $filterStatus,
        ];

        return view('admin/payments/index', $data);
    }
}
