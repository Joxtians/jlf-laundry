<?php 
session_start();
include '../koneksi.php';

// Ambil Data User
$username = $_SESSION['username'];
$userQ = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
$userData = mysqli_fetch_assoc($userQ);
$isMember = $userData['is_member'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pesan Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary-custom text-white p-4 rounded-top-4">
                    <h4 class="mb-0 fw-bold">Form Pemesanan Laundry</h4>
                    <p class="mb-0 opacity-75">Isi data penjemputan dengan lengkap</p>
                </div>
                <div class="card-body p-4">
                    
                    <?php if($isMember): ?>
                        <div class="alert alert-warning d-flex align-items-center mb-4">
                            <i class="bi bi-star-fill fs-4 me-3"></i>
                            <div>
                                <strong>Anda adalah Member VIP!</strong>
                                <br>Nikmati Gratis Ongkir & Prioritas Pengerjaan.
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="../logic/proses_pesanan.php" method="POST" id="formPesanan">
                        <input type="hidden" name="user_id" value="<?= $userData['id']; ?>">
                        <input type="hidden" name="is_member" value="<?= $isMember; ?>">

                        <div class="mb-3">
                            <label class="fw-bold mb-2">Pilih Jenis Layanan</label>
                            <select name="layanan_id" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Paket --</option>
                                <?php 
                                $layanan = mysqli_query($koneksi, "SELECT * FROM layanan");
                                while($l = mysqli_fetch_array($layanan)){
                                    echo "<option value='$l[id]'>$l[nama_layanan] - Rp ".number_format($l['harga_per_kg'])."/kg</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold mb-2">Perkiraan Berat (Kg)</label>
                            <input type="number" name="berat" class="form-control" placeholder="Contoh: 3" required>
                            <small class="text-muted">*Berat pasti akan ditimbang ulang oleh petugas.</small>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="fw-bold mb-2">Alamat Penjemputan</label>
                            <textarea name="alamat" id="alamat_jemput" class="form-control" rows="3" placeholder="Jl. Contoh No. 123, Medan..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-2">Titik Lokasi (Share Location)</label>
                            <div class="d-grid gap-2 mb-2">
                                <button type="button" class="btn btn-outline-primary py-2" onclick="getLocation()">
                                    <i class="bi bi-geo-alt-fill me-2"></i>Ambil Lokasi Saya Saat Ini
                                </button>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-link-45deg"></i></span>
                                <input type="text" id="link_maps" name="link_maps" class="form-control bg-light" placeholder="Koordinat otomatis muncul disini..." required readonly>
                            </div>
                            <small id="status_lokasi" class="text-muted d-block mt-1">
                                *Pastikan GPS aktif & izinkan akses lokasi browser.
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-2">Gunakan Voucher Hemat</label>
                            <select name="voucher_claimed_id" class="form-select border-success text-success fw-bold">
                                <option value="">-- Tidak Pakai Voucher --</option>
                                <?php 
                                $qVoucherUser = mysqli_query($koneksi, "SELECT uv.id, v.kode_voucher, v.potongan, v.min_belanja 
                                                                        FROM user_vouchers uv 
                                                                        JOIN vouchers v ON uv.voucher_id = v.id 
                                                                        WHERE uv.user_id = '$userData[id]' AND uv.status = 'claimed'");
                                while($vu = mysqli_fetch_array($qVoucherUser)){
                                    echo "<option value='$vu[id]'>$vu[kode_voucher] (Hemat Rp ".number_format($vu['potongan']).") - Min. Belanja Rp ".number_format($vu['min_belanja'])."</option>";
                                }
                                ?>
                            </select>
                            <div class="mt-2">
                                <a href="voucher.php" class="text-decoration-none small fw-bold">Lihat & Klaim Voucher Lainnya →</a>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-2">Metode Pembayaran</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="metode_bayar" id="bayar_cash" value="Cash" checked>
                                    <label class="btn btn-outline-primary w-100 py-3 fw-bold" for="bayar_cash">
                                        <i class="bi bi-cash-stack fs-4 d-block mb-1"></i>
                                        Cash
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="metode_bayar" id="bayar_transfer" value="Transfer">
                                    <label class="btn btn-outline-primary w-100 py-3 fw-bold" for="bayar_transfer">
                                        <i class="bi bi-wallet2 fs-4 d-block mb-1"></i>
                                        Transfer
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="button" class="btn btn-primary-custom py-3 fw-bold rounded-pill shadow-sm" onclick="konfirmasiPesanan()">
                                PESAN SEKARANG <i class="bi bi-arrow-right-circle ms-2"></i>
                            </button>
                            <a href="halaman_pelanggan.php" class="btn btn-light text-muted border-0 small">Batal & Kembali</a>
                        </div>
                    
                    </form> </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <i class="bi bi-question-circle-fill text-primary-custom display-1"></i>
                </div>
                <h4 class="fw-bold mb-3">Sudah Yakin dengan Pesanan?</h4>
                <p class="text-muted mb-4">Pastikan alamat jemput dan metode pembayaran sudah sesuai.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cek Lagi</button>
                    <button type="button" class="btn btn-primary-custom w-50 fw-bold" onclick="submitFormAsli()">Ya, Lanjutkan!</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/location.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function konfirmasiPesanan() {
        var myModal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
        myModal.show();
    }

    function submitFormAsli() {
        // Submit form dengan ID spesifik
        document.getElementById('formPesanan').submit();
    }
</script>

</body>
</html>