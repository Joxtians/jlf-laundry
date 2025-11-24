<?php 
$pageTitle = "Daftar Member - JLF & CO Laundry";
include 'includes/header.php'; 
?>

<div class="auth-wrapper">
    <div class="auth-card-modern">
        
        <div class="auth-banner" style="background: linear-gradient(135deg, var(--secondary-color) 0%, #78350f 100%);">
            <div style="position: relative; z-index: 2;">
                <h2 class="fw-bold mb-3">Bergabunglah Bersama Kami!</h2>
                <p class="opacity-90 mb-4">Dapatkan kemudahan notifikasi status cucian via WhatsApp dan struk digital via Email.</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-whatsapp me-2 text-warning"></i> Notifikasi Realtime</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2 text-warning"></i> Struk Pembayaran Digital</li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-2 text-warning"></i> Layanan Antar Jemput</li>
                </ul>
            </div>
        </div>

        <div class="auth-form-side">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary-custom">Buat Akun Baru</h3>
                <p class="text-muted small">Isi data diri Anda dengan lengkap</p>
            </div>

            <?php if(isset($_GET['pesan']) && $_GET['pesan'] == "gagal"): ?>
                <div class="alert alert-danger py-2 small text-center">
                    Gagal mendaftar! Email mungkin sudah terdaftar.
                </div>
            <?php endif; ?>

            <form action="logic/proses_register.php" method="post">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">NAMA LENGKAP</label>
                    <input type="text" name="nama" class="form-control form-control-modern" placeholder="Contoh: username" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">EMAIL AKTIF</label>
                    <input type="email" name="email" class="form-control form-control-modern" placeholder="email" required>
                    <div class="form-text" style="font-size: 11px;">Digunakan untuk pengiriman struk & Login.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">NOMOR WHATSAPP</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3">+62</span>
                        <input type="number" name="no_hp" class="form-control form-control-modern border-start-0" placeholder="..." required>
                    </div>
                    <div class="form-text" style="font-size: 11px;">Kurir akan menghubungi nomor ini.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">PASSWORD</label>
                    <input type="password" name="password" class="form-control form-control-modern" placeholder="Buat password aman" required>
                </div>

                <button type="submit" class="btn btn-accent w-100 py-2 fw-bold shadow-sm mb-3">
                    DAFTAR SEKARANG
                </button>
            </form>

            <div class="text-center mt-2">
                <p class="small text-muted mb-0">Sudah punya akun? <a href="login.php" class="fw-bold text-primary-custom text-decoration-none">Login disini</a></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>