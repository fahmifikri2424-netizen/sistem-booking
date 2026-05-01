<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Staff extends BaseController
{
    public function index()
    {
        return view('staff/dashboard'); //menampilkan dashboard staff
    }
}
