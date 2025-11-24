<?php 
session_start();
include '../koneksi.php';
$id_pesanan = $_GET['id'];


if(isset($_POST['upload_bukti'])){
    $nama_file = $_FILES['bukti']['name'];
    $tmp_file = $_FILES['bukti']['tmp_name'];
    
    
    $nama_baru = "bukti_".$id_pesanan."_".time().".".pathinfo($nama_file, PATHINFO_EXTENSION);
    $path = "../assets/images/bukti_bayar/".$nama_baru;
    
    if(!is_dir("../assets/images/bukti_bayar")) mkdir("../assets/images/bukti_bayar");

    if(move_uploaded_file($tmp_file, $path)){
        mysqli_query($koneksi, "UPDATE pesanan SET bukti_bayar='$nama_baru', status_bayar='Menunggu Verifikasi' WHERE id='$id_pesanan'");
        echo "<script>alert('Bukti bayar berhasil diupload! Tunggu verifikasi admin.'); window.location='detail_pesanan.php?id=$id_pesanan';</script>";
    }
}


$query = mysqli_query($koneksi, "SELECT p.*, l.nama_layanan, k.nama_kurir, k.no_hp as hp_kurir 
                                 FROM pesanan p 
                                 JOIN layanan l ON p.layanan_id = l.id 
                                 LEFT JOIN kurir k ON p.kurir_id = k.id 
                                 WHERE p.id='$id_pesanan'");
$d = mysqli_fetch_assoc($query);


$step = 1;
if($d['status'] == 'Dijemput') $step = 2;
if($d['status'] == 'Dicuci') $step = 3;
if($d['status'] == 'Diantar') $step = 4;
if($d['status'] == 'Selesai') $step = 5;
$width = ($step / 5) * 100;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Pesanan #<?= $id_pesanan; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <div class="card border-0 shadow rounded-4 mb-4 text-center">
                <div class="card-body p-5">
                    
                    <?php if($d['status_bayar'] == 'Lunas'): ?>
                        <i class="bi bi-check-circle-fill text-success display-1 mb-3"></i>
                        <h3 class="fw-bold text-success">Pembayaran Lunas!</h3>
                        <p class="text-muted">Terima kasih, pesanan Anda sedang diproses.</p>
                    <?php elseif($d['status_bayar'] == 'Menunggu Verifikasi'): ?>
                        <i class="bi bi-hourglass-split text-warning display-1 mb-3"></i>
                        <h3 class="fw-bold text-warning">Menunggu Verifikasi</h3>
                        <p class="text-muted">Bukti bayar sudah dikirim. Admin sedang mengecek.</p>
                    <?php else: ?>
                        <i class="bi bi-bag-check-fill text-primary-custom display-1 mb-3"></i>
                        <h3 class="fw-bold">Pesanan Berhasil Dibuat!</h3>
                    <?php endif; ?>

                    <div class="bg-light p-3 rounded-3 border border-dashed my-4">
                        <small class="text-uppercase text-muted fw-bold">Kode Pembayaran Anda</small>
                        <h1 class="fw-bold text-primary-custom letter-spacing-2 mb-0"><?= $d['kode_pembayaran']; ?></h1>
                    </div>
                    
                    <h4 class="fw-bold">Total: Rp <?= number_format($d['total_bayar']); ?></h4>
                    <span class="badge bg-secondary mb-3">Metode: <?= $d['metode_bayar']; ?></span>
                    
                    <hr>

                    <?php if($d['metode_bayar'] == 'Transfer' && $d['status_bayar'] == 'Belum Lunas'): ?>
                        
                        <div class="alert alert-info border-0 d-flex align-items-center mb-4 text-start">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div class="small">Silakan transfer sesuai nominal ke salah satu rekening di bawah ini:</div>
                        </div>

                        <div class="d-flex align-items-center border rounded-3 p-3 mb-3">
                            <img src="../assets/images/dana.jpeg" alt="DANA" style="height: 40px; width: 80px; object-fit: contain;">
                            <div class="ms-3 flex-grow-1 text-start">
                                <h6 class="fw-bold mb-0">0838-9045-2551</h6>
                                <small class="text-muted">a.n Joxtian Mangiring Tua Sirait</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('083890452551')"><i class="bi bi-files"></i> Salin</button>
                        </div>

                        <div class="d-flex align-items-center border rounded-3 p-3 mb-3">
                            <img src="../assets/images/gopay.png" alt="GOPAY" style="height: 40px; width: 80px; object-fit: contain;">
                            <div class="ms-3 flex-grow-1 text-start">
                                <h6 class="fw-bold mb-0">0838-9045-2551</h6>
                                <small class="text-muted">a.n Joxtian Mangiring Tua Sirait</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('083890452551')"><i class="bi bi-files"></i> Salin</button>
                        </div>

                        <div class="d-flex align-items-center border rounded-3 p-3 mb-3">
                            <img src="../assets/images/bri.jpeg" alt="BRI" style="height: 40px; width: 80px; object-fit: contain;">
                            <div class="ms-3 flex-grow-1 text-start">
                                <h6 class="fw-bold mb-0">3539-0104-4072-532</h6>
                                <small class="text-muted">a.n Joxtian Mangiring Tua Sirait</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('353901044072532')"><i class="bi bi-files"></i> Salin</button>
                        </div>

                        <div class="text-center text-muted small mb-4">
                            *E-Wallet lain belum tersedia saat ini.
                        </div>

                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-cloud-upload me-2"></i>Konfirmasi Pembayaran</h6>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3 text-start">
                                        <label class="form-label small text-muted">Upload Bukti Transfer (Foto/Screenshot)</label>
                                        <input type="file" name="bukti" class="form-control" required accept="image/*">
                                    </div>
                                    <button type="submit" name="upload_bukti" class="btn btn-primary-custom w-100 fw-bold py-2">
                                        KIRIM BUKTI BAYAR <i class="bi bi-send-fill ms-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    <?php elseif($d['metode_bayar'] == 'Cash' && $d['status_bayar'] == 'Belum Lunas'): ?>
                        <div class="alert alert-warning text-center">
                            <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Metode Cash</strong><br>
                            Silakan lakukan pembayaran tunai kepada kurir saat penjemputan atau pengantaran laundry.
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="card border-0 shadow rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Status Pesanan</h5>
                    <div class="progress mb-4" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $width; ?>%"></div>
                    </div>
                    <div class="d-flex justify-content-between text-center small">
                        <div class="<?= ($step>=1)?'text-success fw-bold':'text-muted'; ?>">Pending</div>
                        <div class="<?= ($step>=2)?'text-success fw-bold':'text-muted'; ?>">Dijemput</div>
                        <div class="<?= ($step>=3)?'text-success fw-bold':'text-muted'; ?>">Dicuci</div>
                        <div class="<?= ($step>=5)?'text-success fw-bold':'text-muted'; ?>">Selesai</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="halaman_pelanggan.php" class="btn btn-outline-primary rounded-pill px-4">Kembali ke Halaman pelanggan</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>