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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css"> 
    
    <style>
       
        @media (max-width: 576px) {
            h1.fw-bold { font-size: 1.8rem !important; } 
            .card-body { padding: 1.5rem !important; }
            .container { padding-left: 15px; padding-right: 15px; }
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-12">
            
            <div class="card border-0 shadow rounded-4 mb-3 text-center overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    
                    <?php if($d['status_bayar'] == 'Lunas'): ?>
                        <div class="mb-3 text-success">
                            <i class="bi bi-check-circle-fill display-3"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-2">Pembayaran Lunas!</h3>
                        <p class="text-muted small">Terima kasih, pesanan sedang diproses.</p>
                    <?php elseif($d['status_bayar'] == 'Menunggu Verifikasi'): ?>
                        <div class="mb-3 text-warning">
                            <i class="bi bi-hourglass-split display-3"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-2">Menunggu Verifikasi</h3>
                        <p class="text-muted small">Bukti bayar sedang dicek Admin.</p>
                    <?php else: ?>
                        <div class="mb-3 text-primary-custom">
                            <i class="bi bi-bag-check-fill display-3"></i>
                        </div>
                        <h3 class="fw-bold mb-2">Pesanan Berhasil!</h3>
                        <p class="text-muted small">Silakan lakukan pembayaran.</p>
                    <?php endif; ?>

                    <div class="bg-light p-3 rounded-3 border border-dashed my-4">
                        <small class="text-uppercase text-muted fw-bold d-block mb-1">Kode Pembayaran Anda</small>
                        <h1 class="fw-bold text-primary-custom mb-0 text-break"><?= $d['kode_pembayaran']; ?></h1>
                    </div>
                    
                    <h4 class="fw-bold">Total: Rp <?= number_format($d['total_bayar']); ?></h4>
                    <span class="badge bg-secondary mb-3 px-3 py-2">Metode: <?= $d['metode_bayar']; ?></span>
                    
                    <hr>

                    <?php if($d['metode_bayar'] == 'Transfer' && $d['status_bayar'] == 'Belum Lunas'): ?>
                        
                        <div class="alert alert-info border-0 d-flex align-items-center mb-4 text-start p-2">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div class="small lh-sm">Silakan transfer sesuai nominal ke salah satu rekening di bawah:</div>
                        </div>

                        <div class="d-flex align-items-center border rounded-3 p-3 mb-3 bg-white">
                            <img src="../assets/images/dana.jpeg" alt="DANA" style="height: 30px; width: 60px; object-fit: contain;">
                            <div class="ms-3 flex-grow-1 text-start overflow-hidden">
                                <h6 class="fw-bold mb-0 small">0838-9045-2551</h6>
                                <small class="text-muted d-block text-truncate" style="font-size: 10px;">a.n Joxtian Mangiring Tua Sirait</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('083890452551')"><i class="bi bi-files"></i></button>
                        </div>

                        <div class="d-flex align-items-center border rounded-3 p-3 mb-3 bg-white">
                            <img src="../assets/images/gopay.png" alt="GOPAY" style="height: 30px; width: 60px; object-fit: contain;">
                            <div class="ms-3 flex-grow-1 text-start overflow-hidden">
                                <h6 class="fw-bold mb-0 small">0838-9045-2551</h6>
                                <small class="text-muted d-block text-truncate" style="font-size: 10px;">a.n Joxtian Mangiring Tua Sirait</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('083890452551')"><i class="bi bi-files"></i></button>
                        </div>

                        <div class="d-flex align-items-center border rounded-3 p-3 mb-3 bg-white">
                            <img src="../assets/images/bri.jpeg" alt="BRI" style="height: 30px; width: 60px; object-fit: contain;">
                            <div class="ms-3 flex-grow-1 text-start overflow-hidden">
                                <h6 class="fw-bold mb-0 small">3539-0104-4072-532</h6>
                                <small class="text-muted d-block text-truncate" style="font-size: 10px;">a.n Joxtian Mangiring Tua Sirait</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('353901044072532')"><i class="bi bi-files"></i></button>
                        </div>

                        <div class="text-center text-muted mb-4" style="font-size: 10px;">
                            *E-Wallet lain belum tersedia saat ini.
                        </div>

                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-3 small"><i class="bi bi-cloud-upload me-2"></i>Konfirmasi Pembayaran</h6>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3 text-start">
                                        <label class="form-label small text-muted">Upload Bukti Transfer</label>
                                        <input type="file" name="bukti" class="form-control form-control-sm" required accept="image/*">
                                    </div>
                                    <button type="submit" name="upload_bukti" class="btn btn-primary-custom w-100 fw-bold py-2 btn-sm">
                                        KIRIM BUKTI BAYAR <i class="bi bi-send-fill ms-1"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    <?php elseif($d['metode_bayar'] == 'Cash' && $d['status_bayar'] == 'Belum Lunas'): ?>
                        <div class="alert alert-warning text-center small p-2">
                            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i>Metode Cash</strong><br>
                            Silakan bayar tunai ke kurir saat dijemput.
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="card border-0 shadow rounded-4 mb-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Status Pesanan</h5>
                    <div class="progress mb-4" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $width; ?>%"></div>
                    </div>
                    <div class="d-flex justify-content-between text-center" style="font-size: 10px;">
                        <div class="<?= ($step>=1)?'text-success fw-bold':'text-muted'; ?>">Pending</div>
                        <div class="<?= ($step>=2)?'text-success fw-bold':'text-muted'; ?>">Dijemput</div>
                        <div class="<?= ($step>=3)?'text-success fw-bold':'text-muted'; ?>">Dicuci</div>
                        <div class="<?= ($step>=5)?'text-success fw-bold':'text-muted'; ?>">Selesai</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center pb-5">
                <a href="halaman_pelanggan.php" class="btn btn-outline-primary rounded-pill px-4 btn-sm">Kembali ke Dashboard</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>