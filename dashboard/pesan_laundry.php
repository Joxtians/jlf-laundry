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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css"> 
    
    <style>
        /* CSS Khusus agar pas di HP */
        @media (max-width: 576px) {
            .container { padding-left: 10px; padding-right: 10px; }
            .card-body { padding: 1.25rem !important; }
            h5.fw-bold { font-size: 1.1rem; }
            .btn-outline-primary, .btn-primary-custom { font-size: 0.9rem; padding: 10px; }
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-3"> <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-12"> 
            
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden"> 
                <div class="card-header bg-primary-custom text-white p-3 p-md-4">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-basket2 me-2"></i>Form Pemesanan</h5> 
                    <p class="mb-0 opacity-75 small">Isi data penjemputan dengan lengkap</p>
                </div>
                
                <div class="card-body">
                    <?php if($isMember): ?>
                        <div class="alert alert-warning d-flex align-items-center mb-4 p-2">
                            <i class="bi bi-star-fill fs-4 me-3"></i>
                            <div class="small lh-sm">
                                <strong>Member VIP Aktif!</strong><br>
                                Gratis Ongkir & Prioritas.
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="../logic/proses_pesanan.php" method="POST" id="formPesanan">
                        <input type="hidden" name="user_id" value="<?= $userData['id']; ?>">
                        <input type="hidden" name="is_member" value="<?= $isMember; ?>">

                        <div class="mb-3">
                            <label class="fw-bold mb-1 small text-secondary">JENIS LAYANAN</label>
                            <select name="layanan_id" class="form-select" required>
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
                            <label class="fw-bold mb-1 small text-secondary">PERKIRAAN BERAT (KG)</label>
                            <input type="number" name="berat" class="form-control" placeholder="Contoh: 3" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold mb-1 small text-secondary">ALAMAT LENGKAP</label>
                            <textarea name="alamat" id="alamat_jemput" class="form-control" rows="2" placeholder="Nama Jalan, Nomor Rumah..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-1 small text-secondary">TITIK PETA (GPS)</label>
                            <div class="d-grid gap-2 mb-2">
                                <button type="button" class="btn btn-outline-primary" onclick="getLocation()">
                                    <i class="bi bi-geo-alt-fill me-2"></i>Ambil Lokasi Saya
                                </button>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-link-45deg"></i></span>
                                <input type="text" id="link_maps" name="link_maps" class="form-control bg-light" placeholder="Koordinat otomatis..." required>
                            </div>
                            <small id="status_lokasi" class="text-muted d-block mt-1" style="font-size: 10px;">
                                *Izinkan akses lokasi browser saat muncul popup.
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-1 small text-secondary">VOUCHER HEMAT</label>
                            <select name="voucher_claimed_id" class="form-select border-success text-success fw-bold">
                                <option value="">-- Tidak Pakai --</option>
                                <?php 
                                $qVoucherUser = mysqli_query($koneksi, "SELECT uv.id, v.kode_voucher, v.potongan 
                                                                        FROM user_vouchers uv 
                                                                        JOIN vouchers v ON uv.voucher_id = v.id 
                                                                        WHERE uv.user_id = '$userData[id]' AND uv.status = 'claimed'");
                                while($vu = mysqli_fetch_array($qVoucherUser)){
                                    echo "<option value='$vu[id]'>$vu[kode_voucher] (Hemat Rp ".number_format($vu['potongan']).")</option>";
                                }
                                ?>
                            </select>
                            <div class="mt-1 text-end">
                                <a href="voucher.php" class="text-decoration-none small fw-bold" style="font-size: 11px;">Cari Voucher Lain &rarr;</a>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-2 small text-secondary">METODE PEMBAYARAN</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="metode_bayar" id="bayar_cash" value="Cash" checked>
                                    <label class="btn btn-outline-secondary w-100 py-2 small" for="bayar_cash">
                                        <i class="bi bi-cash-stack me-1"></i> Cash
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="metode_bayar" id="bayar_transfer" value="Transfer">
                                    <label class="btn btn-outline-secondary w-100 py-2 small" for="bayar_transfer">
                                        <i class="bi bi-wallet2 me-1"></i> Transfer
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary-custom py-3 fw-bold rounded-pill shadow" onclick="konfirmasiPesanan()">
                                PESAN SEKARANG
                            </button>
                            <a href="halaman_pelanggan.php" class="btn btn-link text-muted text-decoration-none small">Batal</a>
                        </div>
                    
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm"> <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3 text-primary-custom">
                    <i class="bi bi-question-circle-fill display-4"></i>
                </div>
                <h6 class="fw-bold mb-2">Sudah Yakin?</h6>
                <p class="text-muted small mb-4">Pastikan alamat dan metode bayar sudah benar.</p>
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" class="btn btn-light w-100 btn-sm" data-bs-dismiss="modal">Cek Lagi</button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-primary-custom w-100 btn-sm" onclick="submitFormAsli()">Ya, Pesan</button>
                    </div>
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
        document.getElementById('formPesanan').submit();
    }
</script>

</body>
</html>
