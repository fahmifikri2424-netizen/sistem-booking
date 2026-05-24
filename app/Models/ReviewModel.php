<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'id_review';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_booking', 'rating', 'komentar'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'id_booking' => 'required|integer',
        'rating'     => 'required|integer|greater_than[0]|less_than[6]',
        'komentar'   => 'permit_empty|max_length[1000]',
    ];

    protected $validationMessages = [
        'rating' => [
            'required'     => 'Rating wajib dipilih.',
            'greater_than' => 'Rating minimal 1 bintang.',
            'less_than'    => 'Rating maksimal 5 bintang.',
        ],
    ];

    protected $skipValidation = false;
}
