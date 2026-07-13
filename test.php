<?php
require 'vendor/autoload.php';
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();
$res = $db->query('SELECT id_booking, id_staff, id_schedule, tanggal_booking, status_booking FROM bookings')->getResultArray();
print_r($res);
