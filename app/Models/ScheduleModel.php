<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table            = 'schedules';
    protected $primaryKey       = 'id_schedule';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'kapasitas',
        'status'
    ];

    // timestamps
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // validation
    protected $validationRules = [
        'tanggal' => 'required',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
        'kapasitas' => 'required|integer',
        'status' => 'required'
    ];

    protected $validationMessages = [
        'tanggal' => [
            'required' => 'Tanggal wajib diisi'
        ],
        'jam_mulai' => [
            'required' => 'Jam mulai wajib diisi'
        ],
        'jam_selesai' => [
            'required' => 'Jam selesai wajib diisi'
        ],
        'kapasitas' => [
            'required' => 'Kapasitas wajib diisi',
            'integer'  => 'Kapasitas harus angka'
        ],
        'status' => [
            'required' => 'Status wajib diisi'
        ]
    ];

    protected $skipValidation = false;
    // callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}