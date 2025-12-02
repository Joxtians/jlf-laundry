<?php 
$pageTitle = "Login - JLF & CO Laundry";
include 'includes/header.php'; 
?>

<div class="auth-wrapper">
    <div class="auth-card-modern">
        
        <div class="auth-banner">
            <div style="position: relative; z-index: 2;">
                <h2 class="fw-bold mb-3">Selamat Datang Kembali!</h2>
                <p class="opacity-75 mb-4">Masuk untuk memantau status cucian Anda secara real-time. Layanan laundry premium dalam genggaman.</p>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-shield-check fs-2 text-warning"></i>
                    <span>Jaminan Kebersihan 100%</span>
                </div>
                <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill mt-4">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>

        <div class="auth-form-side">
            <div class="text-center mb-4">
                <img src="assets/images/Logo.jpg" alt="Logo" width="60" class="rounded-circle mb-3">
                <h3 class="fw-bold text-primary-custom">Login Member</h3>
                <p class="text-muted small">Silakan masukkan akun Anda</p>
            </div>

            <?php if(isset($_GET['pesan'])): ?>
                <?php if($_GET['pesan'] == "gagal"): ?>
                    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
                        <div><strong>Login Gagal!</strong> Username atau Password salah.</div>
                    </div>
                <?php elseif($_GET['pesan'] == "register"): ?>
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-check-circle-fill flex-shrink-0 me-2"></i>
                        <div><strong>Berhasil!</strong> Akun Anda sudah dibuat. Silakan Login.</div>
                    </div>
                <?php elseif($_GET['pesan'] == "logout"): ?>
                    <div class="alert alert-info py-2 small text-center rounded-3">
                        Anda telah berhasil logout.
                    </div>
                <?php elseif($_GET['pesan'] == "belum_login"): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-circle-fill flex-shrink-0 me-2"></i>
                        <div>Silakan Login untuk mengakses halaman tersebut.</div>
                    </div>
                <?php elseif($_GET['pesan'] == "reset_sukses"): ?>
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-check-circle-fill flex-shrink-0 me-2"></i>
                        <div><strong>Sukses!</strong> Password berhasil diubah. Silakan Login.</div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <form action="logic/cek_login.php" method="post">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">EMAIL</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="text" name="username" class="form-control form-control-modern border-start-0" 
                               placeholder="Masukkan email anda" 
                               value="<?= isset($_GET['email']) ? $_GET['email'] : ''; ?>" 
                               required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <label class="form-label small fw-bold text-secondary">PASSWORD</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control form-control-modern border-start-0" placeholder="" required>
                    </div>
                    
                    <div class="text-end mt-2">
                        <a href="lupa_password.php" class="text-decoration-none small text-primary fw-bold">Lupa Password?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold shadow-sm mb-3">
                    MASUK SEKARANG <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center">
                <p class="text-muted small mb-0">Belum punya akun?</p>
                <a href="register.php" class="fw-bold text-primary-custom text-decoration-none">Daftar Member Baru</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>