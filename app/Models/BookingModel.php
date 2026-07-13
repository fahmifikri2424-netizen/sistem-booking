<?php

namespace App\Models; 

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'id_booking';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'id_staff',
        'id_service',
        'id_schedule',
        'kode_booking',
        'tanggal_booking',
        'catatan',
        'status_booking',
        'status_pembayaran',
        'google_calendar_event_id',
    ];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_user' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Pelanggan wajib dipilih.'
            ]
        ],

        'id_staff' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Barber wajib dipilih.'
            ]
        ],

        'id_service' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Layanan wajib dipilih.'
            ]
        ],

        'id_schedule' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Jadwal wajib dipilih.'
            ]
        ],

        'tanggal_booking' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Tanggal booking wajib diisi.'
            ]
        ],

    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}