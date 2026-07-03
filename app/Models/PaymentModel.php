<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id_payment';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_booking',
        'metode',
        'jumlah',
        'status',
        'snap_token',
        'transaction_id',
        'payment_type',
        'payment_time',
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /**
     * Cari payment berdasarkan id_booking (ambil yang terbaru).
     */
    public function findByBooking(int $idBooking): ?array
    {
        return $this->where('id_booking', $idBooking)
                    ->orderBy('id_payment', 'DESC')
                    ->first();
    }

    /**
     * Cari payment berdasarkan transaction_id Midtrans.
     */
    public function findByTransactionId(string $transactionId): ?array
    {
        return $this->where('transaction_id', $transactionId)->first();
    }
}
