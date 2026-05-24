<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table            = 'staffs';
    protected $primaryKey       = 'id_staff';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_user', 'spesialisasi'];

    // Dates
    protected $useTimestamps = false;

    /**
     * Ambil data staff beserta nama user-nya
     */
    public function getStaffWithUser()
    {
        return $this->select('staffs.*, users.nama')
                    ->join('users', 'users.id_user = staffs.id_user')
                    ->findAll();
    }
}
