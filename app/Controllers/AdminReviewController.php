<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReviewModel;

class AdminReviewController extends BaseController
{
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        // Get all reviews with booking details, user details, and staff details
        $reviews = $this->reviewModel
            ->select('reviews.*, bookings.kode_booking, bookings.tanggal_booking, 
                      services.nama as nama_service, 
                      users.nama as nama_customer,
                      staff_user.nama as nama_staff')
            ->join('bookings', 'bookings.id_booking = reviews.id_booking')
            ->join('services', 'services.id_service = bookings.id_service')
            ->join('users', 'users.id_user = bookings.id_user')
            ->join('staffs', 'staffs.id_staff = bookings.id_staff')
            ->join('users as staff_user', 'staff_user.id_user = staffs.id_user')
            ->orderBy('reviews.id_review', 'DESC')
            ->findAll();

        return view('admin/reviews/index', [
            'reviews' => $reviews
        ]);
    }

    public function delete($id)
    {
        $review = $this->reviewModel->find($id);

        if ($review) {
            $this->reviewModel->delete($id);
            session()->setFlashdata('success', 'Ulasan berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Ulasan tidak ditemukan.');
        }

        return redirect()->to('/admin/reviews');
    }
}
