<?php 
session_start();
include '../koneksi.php';


if(!isset($_SESSION['level']) || $_SESSION['level'] != "pelanggan"){
    header("location:../index.php");
    exit;
}


$username = $_SESSION['username'];


$queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
$userData = mysqli_fetch_assoc($queryUser);

if (!$userData) {
    echo "Error: User tidak ditemukan.";
    exit;
}


$user_id = $userData['id'];
$is_member = $userData['is_member'];


if(isset($_POST['klaim_id'])){
    $v_id = $_POST['klaim_id'];
    
    $cek = mysqli_query($koneksi, "SELECT * FROM user_vouchers WHERE user_id='$user_id' AND voucher_id='$v_id'");
    
    if(mysqli_num_rows($cek) == 0){
    
        mysqli_query($koneksi, "UPDATE vouchers SET kuota = kuota - 1 WHERE id='$v_id'");
        
        $insert = mysqli_query($koneksi, "INSERT INTO user_vouchers (user_id, voucher_id, status, tanggal_klaim) VALUES ('$user_id', '$v_id', 'claimed', NOW())");
        
        if($insert) {
            echo "<script>alert('Voucher Berhasil Diklaim!'); window.location='voucher.php';</script>";
        } else {
            echo "<script>alert('Gagal mengklaim voucher.');</script>";
        }
    } else {
        echo "<script>alert('Anda sudah mengklaim voucher ini sebelumnya!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Voucher Promo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary-custom" href="halaman_pelanggan.php"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        <span class="fw-bold">Voucher Center</span>
    </div>
</nav>

<div class="container pb-5">
    <div class="alert <?= $is_member ? 'alert-warning' : 'alert-secondary'; ?> d-flex align-items-center shadow-sm mb-4">
        <i class="bi <?= $is_member ? 'bi-star-fill text-warning' : 'bi-person-circle'; ?> fs-3 me-3"></i>
        <div>
            <strong>Status: <?= $is_member ? 'Member VIP' : 'Pelanggan Biasa'; ?></strong>
            <br><small><?= $is_member ? 'Anda berhak mengklaim Voucher Eksklusif!' : 'Upgrade ke Member untuk akses promo VIP.'; ?></small>
        </div>
    </div>

    <div class="row g-4">
        <?php 
        $query = mysqli_query($koneksi, "SELECT * FROM vouchers WHERE kuota > 0 AND berlaku_sampai > NOW()");
        
        if(mysqli_num_rows($query) > 0):
            while($v = mysqli_fetch_array($query)):
                
            
                $isAccessible = true;
                if($v['khusus_member'] == 1 && $is_member == 0) $isAccessible = false;
                
            
                $cekKlaim = mysqli_query($koneksi, "SELECT * FROM user_vouchers WHERE user_id='$user_id' AND voucher_id='$v[id]'");
                $sudahKlaim = mysqli_num_rows($cekKlaim) > 0;
            ?>
            
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden hover-up">
                    <?php if($v['khusus_member']): ?>
                        <div class="position-absolute top-0 end-0 bg-warning text-dark px-3 py-1 small fw-bold" style="border-bottom-left-radius: 10px; font-size: 0.75rem;">VIP ONLY</div>
                    <?php endif; ?>

                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary-custom text-white p-3 rounded-3 me-3 text-center d-flex flex-column justify-content-center" style="min-width: 90px; height: 90px;">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">DISKON</small>
                            <h5 class="mb-0 fw-bold">
                                <?= ($v['potongan'] >= 1000) ? (number_format($v['potongan']/1000) . 'k') : $v['potongan']; ?>
                            </h5>
                        </div>
                        
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark"><?= $v['kode_voucher']; ?></h5>
                            <p class="text-muted small mb-2 lh-sm"><?= $v['deskripsi']; ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-danger fw-bold" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>Exp: <?= date('d M', strtotime($v['berlaku_sampai'])); ?>
                                </small>
                                <small class="text-muted bg-light px-2 py-1 rounded" style="font-size: 0.75rem;">
                                    Sisa: <?= $v['kuota']; ?>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                        <?php if(!$isAccessible): ?>
                            <button class="btn btn-secondary btn-sm w-100 opacity-75" disabled>
                                <i class="bi bi-lock-fill me-1"></i>Khusus Member VIP
                            </button>
                        <?php elseif($sudahKlaim): ?>
                            <button class="btn btn-success btn-sm w-100" disabled>
                                <i class="bi bi-check-circle-fill me-1"></i>Sudah Diklaim
                            </button>
                        <?php else: ?>
                            <form method="POST">
                                <input type="hidden" name="klaim_id" value="<?= $v['id']; ?>">
                                <button type="submit" class="btn btn-primary-custom btn-sm w-100 fw-bold">
                                    Klaim Sekarang
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-ticket-perforated fs-1 d-block mb-3"></i>
                    <p>Belum ada voucher promo tersedia saat ini.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>