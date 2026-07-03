<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Sidebar Admin -->
        <?php if(session()->get('role') == 'admin'): ?>
            <li class="nav-heading">Admin Panel</li>

            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'admin' || uri_string() == '') ? "" : "collapsed" ?>" href="<?= base_url('admin') ?>">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'admin/services') !== false) ? "" : "collapsed" ?>" href="<?= base_url('admin/services') ?>">
                    <i class="bi bi-scissors"></i>
                    <span>Services</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'admin/schedules') !== false) ? "" : "collapsed" ?>" href="<?= base_url('admin/schedules') ?>">
                    <i class="bi bi-calendar-event"></i>
                    <span>Jadwal</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'admin/bookings') !== false) ? "" : "collapsed" ?>" href="<?= base_url('admin/bookings') ?>">
                    <i class="bi bi-journal-check"></i>
                    <span>Booking</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'admin/payments') !== false) ? "" : "collapsed" ?>" href="<?= base_url('admin/payments') ?>">
                    <i class="bi bi-wallet2"></i>
                    <span>Laporan Pembayaran</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'admin/staffs') !== false) ? "" : "collapsed" ?>" href="<?= base_url('admin/staffs') ?>">
                    <i class="bi bi-people"></i>
                    <span>Data Staff</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'admin/users') !== false) ? "" : "collapsed" ?>" href="<?= base_url('admin/users') ?>">
                    <i class="bi bi-people"></i>
                    <span>Data User</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'admin/reviews') !== false) ? "" : "collapsed" ?>" href="<?= base_url('admin/reviews') ?>">
                    <i class="bi bi-star"></i>
                    <span>Review</span>
                </a>
            </li>

            <li class="nav-heading mt-3">Akun</li>

            <li class="nav-item">
                <a class="nav-link collapsed text-danger" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right text-danger"></i>
                    <span>Logout</span>
                </a>
            </li>
            
        <?php elseif(session()->get('role') == 'staff'): ?>
            <!-- Sidebar Staff -->
            <li class="nav-heading">Staff Panel</li>

            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'staff') ? "" : "collapsed" ?>" href="<?= base_url('staff') ?>">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'staff/jadwal') !== false) ? "" : "collapsed" ?>" href="<?= base_url('staff/jadwal') ?>">
                    <i class="bi bi-calendar-check"></i>
                    <span>Jadwal Harian</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'staff/update-status') !== false) ? "" : "collapsed" ?>" href="<?= base_url('staff/update-status') ?>">
                    <i class="bi bi-pencil-square"></i>
                    <span>Update Status</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'staff/riwayat') !== false) ? "" : "collapsed" ?>" href="<?= base_url('staff/riwayat') ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Pekerjaan</span>
                </a>
            </li>

            <li class="nav-heading mt-3">Akun</li>

            <li class="nav-item">
                <a class="nav-link collapsed text-danger" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right text-danger"></i>
                    <span>Logout</span>
                </a>
            </li>

        <?php elseif(session()->get('role') == 'customer' || session()->get('role') == 'user'): ?>
            <!-- Sidebar User/Customer -->
            <li class="nav-heading">Menu Pelanggan</li>

            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'user') ? "" : "collapsed" ?>" href="<?= base_url('user') ?>">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'user/layanan') !== false || strpos(uri_string(), 'user/pilih-jadwal') !== false || strpos(uri_string(), 'user/form-booking') !== false) ? "" : "collapsed" ?>" href="<?= base_url('user/layanan') ?>">
                    <i class="bi bi-scissors"></i>
                    <span>Daftar Layanan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (strpos(uri_string(), 'user/riwayat') !== false || strpos(uri_string(), 'user/ulasan') !== false) ? "" : "collapsed" ?>" href="<?= base_url('user/riwayat') ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Booking</span>
                </a>
            </li>

            <li class="nav-heading mt-3">Akun</li>

            <li class="nav-item">
                <a class="nav-link collapsed text-danger" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right text-danger"></i>
                    <span>Logout</span>
                </a>
            </li>

        <?php else: ?>
            <!-- Sidebar role lain -->
            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == '') ? "" : "collapsed" ?>" href="/">
                    <i class="bi bi-grid"></i>
                    <span>Home</span>
                </a>
            </li>
        <?php endif; ?>

    </ul>

</aside><!-- End Sidebar-->