<?php 
$pageTitle = "Reset Password - JLF Laundry";
include 'includes/header.php'; 
?>

<div class="auth-wrapper">
    <div class="card border-0 shadow-lg rounded-4 p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <div class="bg-primary-custom text-white rounded-circle d-inline-flex p-3 mb-3">
                <i class="bi bi-key fs-2"></i>
            </div>
            <h4 class="fw-bold text-primary-custom">Lupa Password?</h4>
            <p class="text-muted small">Masukkan Email dan Nomor WhatsApp yang terdaftar untuk mereset password Anda.</p>
        </div>

        <?php if(isset($_GET['pesan']) && $_GET['pesan'] == "gagal"): ?>
            <div class="alert alert-danger small text-center">
                Data tidak ditemukan! Pastikan Email dan No HP benar.
            </div>
        <?php endif; ?>

        <form action="logic/proses_reset.php" method="POST">
            <div class="mb-3">
                <label class="fw-bold small text-secondary">EMAIL</label>
                <input type="email" name="email" class="form-control" placeholder="" required>
            </div>
            <div class="mb-4">
                <label class="fw-bold small text-secondary">NO Telepon</label>
                <input type="number" name="no_hp" class="form-control" placeholder="" required>
            </div>
            
            <button type="submit" name="cek_user" class="btn btn-primary-custom w-100 fw-bold mb-3">VERIFIKASI AKUN</button>
            <a href="login.php" class="btn btn-light w-100 border text-muted">Batal & Kembali</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>