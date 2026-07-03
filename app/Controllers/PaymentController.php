<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\PaymentModel;
use App\Models\UserModel;
use Config\Midtrans as MidtransConfig;

class PaymentController extends BaseController
{
    protected BookingModel  $bookingModel;
    protected PaymentModel  $paymentModel;
    protected MidtransConfig $midtransConfig;

    public function __construct()
    {
        $this->bookingModel   = new BookingModel();
        $this->paymentModel   = new PaymentModel();
        $this->midtransConfig = new MidtransConfig();

        // Inisialisasi Midtrans SDK global
        \Midtrans\Config::$serverKey    = $this->midtransConfig->serverKey;
        \Midtrans\Config::$clientKey    = $this->midtransConfig->clientKey;
        \Midtrans\Config::$isProduction = $this->midtransConfig->isProduction;
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    // =========================================================================
    // ENDPOINT 1: POST /user/payment/create/{id_booking}
    // Membuat transaksi Midtrans dan mengembalikan snap_token (JSON)
    // =========================================================================

    public function createTransaction(int $idBooking)
    {
        $idUser = session()->get('id_user');

        // Ambil data booking lengkap
        $booking = $this->bookingModel
            ->select('bookings.*, services.nama as nama_service, services.harga,
                      schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai,
                      users.nama as nama_pelanggan, users.email as email_pelanggan,
                      users.telepon')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->join('users', 'users.id_user = bookings.id_user')
            ->where('bookings.id_booking', $idBooking)
            ->where('bookings.id_user', $idUser)
            ->first();

        if (!$booking) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Booking tidak ditemukan.',
            ])->setStatusCode(404);
        }

        if ($booking['status_booking'] !== 'dikonfirmasi') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Booking belum dikonfirmasi oleh admin.',
            ])->setStatusCode(400);
        }

        if ($booking['status_pembayaran'] === 'sudah_bayar') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Booking ini sudah dibayar.',
            ])->setStatusCode(400);
        }

        // Cek apakah sudah ada snap_token aktif
        $existingPayment = $this->paymentModel->findByBooking($idBooking);
        if ($existingPayment && !empty($existingPayment['snap_token']) && $existingPayment['status'] === 'pending') {
            return $this->response->setJSON([
                'status'      => 'success',
                'snap_token'  => $existingPayment['snap_token'],
                'client_key'  => $this->midtransConfig->clientKey,
            ]);
        }

        // Buat transaksi baru ke Midtrans
        try {
            $orderId = $booking['kode_booking'] . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $booking['harga'],
                ],
                'customer_details' => [
                    'first_name' => $booking['nama_pelanggan'],
                    'email'      => $booking['email_pelanggan'],
                    'phone'      => $booking['telepon'] ?? '',
                ],
                'item_details' => [
                    [
                        'id'       => $booking['id_service'],
                        'price'    => (int) $booking['harga'],
                        'quantity' => 1,
                        'name'     => substr($booking['nama_service'], 0, 50),
                    ],
                ],
                'callbacks' => [
                    'finish' => base_url('user/payment/finish/' . $idBooking),
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan payment record ke database
            $this->paymentModel->insert([
                'id_booking'     => $idBooking,
                'metode'         => null,
                'jumlah'         => $booking['harga'],
                'status'         => 'pending',
                'snap_token'     => $snapToken,
                'transaction_id' => $orderId,
                'payment_type'   => null,
                'payment_time'   => null,
            ]);

            return $this->response->setJSON([
                'status'      => 'success',
                'snap_token'  => $snapToken,
                'client_key'  => $this->midtransConfig->clientKey,
            ]);

        } catch (\Exception $e) {
            log_message('error', '[PaymentController::createTransaction] ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    // =========================================================================
    // ENDPOINT 2: POST /api/payment/callback
    // Menerima webhook notifikasi dari Midtrans
    // =========================================================================

    public function callback()
    {
        // Ambil payload JSON dari Midtrans
        $payload = $this->request->getJSON(true);

        if (empty($payload)) {
            $payload = json_decode(file_get_contents('php://input'), true);
        }

        if (!$payload) {
            return $this->response->setJSON(['message' => 'Invalid payload'])->setStatusCode(400);
        }

        log_message('info', '[PaymentController::callback] Payload: ' . json_encode($payload));

        // Verifikasi signature key
        $signatureKey  = $payload['signature_key']  ?? '';
        $orderId       = $payload['order_id']        ?? '';
        $statusCode    = $payload['status_code']     ?? '';
        $grossAmount   = $payload['gross_amount']    ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        $paymentType   = $payload['payment_type']    ?? '';
        $fraudStatus   = $payload['fraud_status']    ?? '';

        // Hitung signature: SHA512(order_id + status_code + gross_amount + server_key)
        $expectedSignature = hash('sha512',
            $orderId . $statusCode . $grossAmount . $this->midtransConfig->serverKey
        );

        if ($signatureKey !== $expectedSignature) {
            log_message('warning', '[PaymentController::callback] Signature tidak valid untuk order: ' . $orderId);
            return $this->response->setJSON(['message' => 'Invalid signature'])->setStatusCode(403);
        }

        // Cari payment berdasarkan order_id (= transaction_id)
        $payment = $this->paymentModel->findByTransactionId($orderId);

        if (!$payment) {
            // Coba cari dengan prefix kode_booking (tanpa -timestamp)
            $kodeBooking = explode('-', $orderId);
            // order_id = BOOK-XXXXXX-timestamp → ambil BOOK-XXXXXX
            $kodeBookingStr = isset($kodeBooking[0], $kodeBooking[1])
                ? $kodeBooking[0] . '-' . $kodeBooking[1]
                : $orderId;

            $booking = $this->bookingModel->where('kode_booking', $kodeBookingStr)->first();
            if ($booking) {
                $payment = $this->paymentModel->findByBooking($booking['id_booking']);
            }
        }

        if (!$payment) {
            log_message('warning', '[PaymentController::callback] Payment tidak ditemukan untuk order: ' . $orderId);
            return $this->response->setJSON(['message' => 'Payment not found'])->setStatusCode(404);
        }

        $idBooking = $payment['id_booking'];

        // Tentukan status berdasarkan response Midtrans
        $newPaymentStatus = 'pending';
        $newBookingPaymentStatus = 'belum_bayar';

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $newPaymentStatus = 'sukses';
                $newBookingPaymentStatus = 'sudah_bayar';
            }
        } elseif ($transactionStatus === 'settlement') {
            $newPaymentStatus = 'sukses';
            $newBookingPaymentStatus = 'sudah_bayar';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $newPaymentStatus = 'gagal';
            $newBookingPaymentStatus = 'belum_bayar';
        } elseif ($transactionStatus === 'pending') {
            $newPaymentStatus = 'pending';
            $newBookingPaymentStatus = 'belum_bayar';
        }

        // Update tabel payments
        $this->paymentModel->update($payment['id_payment'], [
            'status'         => $newPaymentStatus,
            'metode'         => $paymentType,
            'payment_type'   => $paymentType,
            'transaction_id' => $orderId,
            'payment_time'   => $payload['transaction_time'] ?? null,
        ]);

        // Update status_pembayaran di tabel bookings
        $this->bookingModel->update($idBooking, [
            'status_pembayaran' => $newBookingPaymentStatus,
        ]);

        // Kirim email konfirmasi jika pembayaran sukses
        if ($newPaymentStatus === 'sukses') {
            $this->sendConfirmationEmail($idBooking);
        }

        log_message('info', "[PaymentController::callback] Order {$orderId} → status: {$newPaymentStatus}");

        return $this->response->setJSON(['message' => 'OK'])->setStatusCode(200);
    }

    // =========================================================================
    // ENDPOINT 3: GET /user/payment/finish/{id_booking}
    // Halaman setelah user selesai dari popup Midtrans
    // =========================================================================

    public function finish(int $idBooking)
    {
        $idUser = session()->get('id_user');

        $booking = $this->bookingModel
            ->select('bookings.*, services.nama as nama_service, services.harga,
                      schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
            ->where('bookings.id_booking', $idBooking)
            ->where('bookings.id_user', $idUser)
            ->first();

        if (!$booking) {
            return redirect()->to('/user/riwayat')->with('error', 'Booking tidak ditemukan.');
        }

        $payment = $this->paymentModel->findByBooking($idBooking);

        // --- LOCALHOST WORKAROUND (Pengecekan Manual ke API Midtrans) ---
        // Jika status DB masih pending, cek langsung ke Midtrans via API (berguna jika webhook tidak jalan di localhost)
        if ($payment && $payment['status'] === 'pending' && !empty($payment['transaction_id'])) {
            try {
                $statusRes = \Midtrans\Transaction::status($payment['transaction_id']);
                
                $transactionStatus = $statusRes->transaction_status ?? '';
                $fraudStatus = $statusRes->fraud_status ?? '';
                $paymentType = $statusRes->payment_type ?? '';

                $newPaymentStatus = 'pending';
                $newBookingPaymentStatus = 'belum_bayar';

                if ($transactionStatus === 'capture') {
                    if ($fraudStatus === 'accept') {
                        $newPaymentStatus = 'sukses';
                        $newBookingPaymentStatus = 'sudah_bayar';
                    }
                } elseif ($transactionStatus === 'settlement') {
                    $newPaymentStatus = 'sukses';
                    $newBookingPaymentStatus = 'sudah_bayar';
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $newPaymentStatus = 'gagal';
                    $newBookingPaymentStatus = 'belum_bayar';
                }

                // Jika status di Midtrans ternyata sudah berubah (lunas/batal), update DB!
                if ($newPaymentStatus !== 'pending') {
                    $this->paymentModel->update($payment['id_payment'], [
                        'status'         => $newPaymentStatus,
                        'metode'         => $paymentType,
                        'payment_type'   => $paymentType,
                        'payment_time'   => $statusRes->transaction_time ?? null,
                    ]);

                    $this->bookingModel->update($idBooking, [
                        'status_pembayaran' => $newBookingPaymentStatus,
                    ]);

                    if ($newPaymentStatus === 'sukses') {
                        $this->sendConfirmationEmail($idBooking);
                    }

                    // Refresh data payment & booking di memori untuk ditampilkan ke view
                    $payment['status'] = $newPaymentStatus;
                    $booking['status_pembayaran'] = $newBookingPaymentStatus;
                }
            } catch (\Exception $e) {
                log_message('error', '[Midtrans API Status Check] ' . $e->getMessage());
            }
        }
        // -----------------------------------------------------------------

        return view('user/payment_finish', [
            'booking' => $booking,
            'payment' => $payment,
        ]);
    }

    // =========================================================================
    // PRIVATE: Kirim email konfirmasi pembayaran
    // =========================================================================

    private function sendConfirmationEmail(int $idBooking): void
    {
        try {
            $booking = $this->bookingModel
                ->select('bookings.*, services.nama as nama_service, services.harga,
                          schedules.tanggal, schedules.jam_mulai, schedules.jam_selesai,
                          users.nama as nama_pelanggan, users.email as email_pelanggan,
                          staff_user.nama as nama_staff')
                ->join('services', 'services.id_service = bookings.id_service')
                ->join('schedules', 'schedules.id_schedule = bookings.id_schedule')
                ->join('users', 'users.id_user = bookings.id_user')
                ->join('staffs', 'staffs.id_staff = bookings.id_staff')
                ->join('users as staff_user', 'staff_user.id_user = staffs.id_user')
                ->find($idBooking);

            if (!$booking || empty($booking['email_pelanggan'])) {
                return;
            }

            $email = \Config\Services::email();
            
            $configEmail = config('Email');
            $fromEmail = !empty($configEmail->fromEmail) ? $configEmail->fromEmail : $configEmail->SMTPUser;
            $fromName  = !empty($configEmail->fromName) ? $configEmail->fromName : 'Sistem Booking';

            $email->setFrom($fromEmail, $fromName);
            $email->setTo($booking['email_pelanggan']);
            $email->setSubject('✅ Konfirmasi Pembayaran — ' . $booking['kode_booking']);

            $emailBody = view('email/konfirmasi_pembayaran', ['booking' => $booking]);
            $email->setMessage($emailBody);

            if (!$email->send()) {
                log_message('error', '[PaymentController] Gagal kirim email: ' . $email->printDebugger(['headers']));
            } else {
                log_message('info', '[PaymentController] Email konfirmasi terkirim ke: ' . $booking['email_pelanggan']);
            }

        } catch (\Exception $e) {
            log_message('error', '[PaymentController::sendConfirmationEmail] ' . $e->getMessage());
        }
    }
}
