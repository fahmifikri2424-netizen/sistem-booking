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
                <a class="nav-link collapsed" href="#">
                    <i class="bi bi-wallet2"></i>
                    <span>Pembayaran</span>
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
                <a class="nav-link collapsed" href="#">
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