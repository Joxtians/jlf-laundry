<?php 
session_start();
if(!isset($_SESSION['reset_id_user'])){
    header("location:login.php");
    exit;
}
$pageTitle = "Password Baru - JLF Laundry";
include 'includes/header.php'; 
?>

<div class="auth-wrapper">
    <div class="card border-0 shadow-lg rounded-4 p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-success">Verifikasi Berhasil!</h4>
            <p class="text-muted small">Silakan buat password baru untuk akun Anda.</p>
        </div>

        <form action="logic/proses_reset.php" method="POST">
            <div class="mb-3">
                <label class="fw-bold small text-secondary">PASSWORD BARU</label>
                <input type="password" name="pass_baru" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="mb-4">
                <label class="fw-bold small text-secondary">ULANGI PASSWORD</label>
                <input type="password" name="pass_konfirm" class="form-control" placeholder="Ketik ulang password" required>
            </div>
            
            <button type="submit" name="simpan_password" class="btn btn-success w-100 fw-bold">SIMPAN PASSWORD BARU</button>
        </form>
    </div>
</div>
</body>
</html>