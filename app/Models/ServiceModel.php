<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table            = 'services';
    protected $primaryKey       = 'id_service';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'deskripsi', 'harga', 'durasi', 'foto', 'status'];

    // Validation
    protected $validationRules      = [
        'nama'      => 'required|min_length[3]|max_length[100]',
        'deskripsi' => 'required',
        'harga'     => 'required|numeric',
        'durasi'    => 'required|integer',
        'status'    => 'required|in_list[aktif,nonaktif]'
    ];
    
    protected $validationMessages   = [
        'nama' => [
            'required' => 'Nama layanan harus diisi.',
            'min_length' => 'Nama layanan minimal 3 karakter.',
            'max_length' => 'Nama layanan maksimal 100 karakter.'
        ],
        'deskripsi' => [
            'required' => 'Deskripsi layanan harus diisi.'
        ],
        'harga' => [
            'required' => 'Harga harus diisi.',
            'numeric'  => 'Harga harus berupa angka.'
        ],
        'durasi' => [
            'required' => 'Durasi harus diisi.',
            'integer'  => 'Durasi harus berupa angka bulat (menit).'
        ],
        'status' => [
            'required' => 'Status harus dipilih.',
            'in_list'  => 'Status tidak valid.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
