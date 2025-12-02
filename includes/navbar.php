<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <img src="assets/images/Logo.jpg" alt="Logo" width="40" height="40" class="rounded-circle object-fit-cover">
            <span class="fw-bold text-primary-custom">JLF & CO</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="index.php#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="index.php#layanan">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="index.php#cs">Kontak CS</a>
                </li>
                
                <?php if(isset($_SESSION['level'])): ?>
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle btn btn-light px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                            Halo, <?= $_SESSION['nama']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                            <?php if($_SESSION['level'] == 'admin'): ?>
                                <li><a class="dropdown-item" href="dashboard/halaman_admin.php">Dashboard Admin</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="dashboard/halaman_pelanggan.php">Dashboard Pelanggan</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logic/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary-custom px-4 rounded-pill shadow-sm" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </div>
    </div>
</nav>