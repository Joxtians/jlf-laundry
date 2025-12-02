<?php 
session_start();
include '../koneksi.php';


if(!isset($_SESSION['level']) || $_SESSION['level'] != "admin"){
    header("location:../index.php?pesan=gagal");
    exit;
}

$pageTitle = "Laman Admin - JLF Laundry";




if(isset($_GET['read_notif'])){
    $id = $_GET['read_notif'];
    mysqli_query($koneksi, "UPDATE pesanan SET read_admin = 1 WHERE id = '$id'");
    header("location:halaman_admin.php"); 
    exit;
}


if(isset($_GET['mark_all_read'])){
    mysqli_query($koneksi, "UPDATE pesanan SET read_admin = 1 WHERE read_admin = 0");
    header("location:halaman_admin.php");
    exit;
}


$qNotif = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM pesanan WHERE read_admin = 0");
$dataNotif = mysqli_fetch_assoc($qNotif);
$jumlahNotifAdmin = $dataNotif['jumlah'];


$qIsiNotif = mysqli_query($koneksi, "SELECT p.*, u.nama, l.nama_layanan 
                                      FROM pesanan p 
                                      JOIN user u ON p.user_id = u.id 
                                      JOIN layanan l ON p.layanan_id = l.id
                                      WHERE read_admin = 0 
                                      ORDER BY p.id DESC LIMIT 5");



if(isset($_POST['update_order'])){
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status'];
    $kurir_baru = $_POST['kurir_id'];
    
    
    $update = mysqli_query($koneksi, "UPDATE pesanan SET status='$status_baru', kurir_id='$kurir_baru', read_customer=0 WHERE id='$id_pesanan'");
    

    if($status_baru == 'Diantar') {
        mysqli_query($koneksi, "UPDATE kurir SET status='Sibuk' WHERE id='$kurir_baru'");
    } elseif ($status_baru == 'Selesai') {
        mysqli_query($koneksi, "UPDATE kurir SET status='Standby' WHERE id='$kurir_baru'");
      
        mysqli_query($koneksi, "UPDATE pesanan SET status_bayar='Lunas' WHERE id='$id_pesanan'");
    }
    
    echo "<meta http-equiv='refresh' content='0'>"; 
}


if(isset($_POST['verifikasi_bayar'])){
    $id_pesanan = $_POST['id_pesanan'];
    mysqli_query($koneksi, "UPDATE pesanan SET status_bayar='Lunas' WHERE id='$id_pesanan'");
    echo "<meta http-equiv='refresh' content='0'>"; 
}

$total_income = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_bayar) as total FROM pesanan"))['total'];
$total_order = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pesanan WHERE status != 'Selesai'"));
$kurir_standby = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kurir WHERE status = 'Standby'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .card-stat { transition: all 0.3s; border:none; }
        .card-stat:hover { transform: translateY(-5px); }
        .bg-navy { background-color: var(--primary-color); }
        .dropdown-item { white-space: normal; } /* Teks notif panjang turun ke bawah */
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-navy sticky-top shadow">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand fw-bold" href="#">LAMAN ADMIN</a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navAdmin">
            <div class="ms-auto d-flex align-items-center text-white gap-3 py-2 py-lg-0">
                
                <div class="dropdown">
                    <a href="#" class="position-relative text-white text-decoration-none d-flex align-items-center" id="notifDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-bell-fill fs-4"></i>
                        <?php if($jumlahNotifAdmin > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light animate__animated animate__swing infinite" style="font-size: 0.6rem;">
                                <?= $jumlahNotifAdmin; ?>
                            </span>
                        <?php endif; ?>
                        <span class="d-lg-none ms-2">Notifikasi</span> </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="width: 300px; max-height: 400px; overflow-y: auto;">
                        <li class="dropdown-header fw-bold text-primary-custom bg-light">Pesanan Masuk</li>
                        
                        <?php if($jumlahNotifAdmin > 0): ?>
                            <?php while($notif = mysqli_fetch_array($qIsiNotif)): ?>
                            <li>
                                <a href="halaman_admin.php?read_notif=<?= $notif['id']; ?>" class="dropdown-item p-3 border-bottom hover-bg-light">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-primary-custom text-white rounded-circle p-2 me-3">
                                            <i class="bi bi-cart-plus"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block text-dark"><?= $notif['nama']; ?></span>
                                            <small class="text-muted d-block"><?= $notif['nama_layanan']; ?></small>
                                            <span class="badge bg-success mt-1">Rp <?= number_format($notif['total_bayar']); ?></span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <?php endwhile; ?>
                            <li><a class="dropdown-item text-center small text-primary fw-bold py-3" href="halaman_admin.php?mark_all_read=true">Tandai Semua Dibaca</a></li>
                        <?php else: ?>
                            <li><div class="text-center py-4 text-muted small">Tidak ada pesanan baru</div></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="d-flex align-items-center">
                    <span class="me-3 d-none d-md-block"><i class="bi bi-person-circle me-2"></i><?= $_SESSION['nama']; ?></span>
                    <a href="../logic/logout.php" class="btn btn-sm btn-danger fw-bold">Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid px-3 px-lg-4 py-4">
    
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card card-stat shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary-custom text-white p-3 rounded-3 me-3"><i class="bi bi-wallet2 fs-3"></i></div>
                    <div><p class="text-muted mb-0 small">Total Pendapatan</p><h4 class="fw-bold mb-0">Rp <?= number_format($total_income); ?></h4></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card card-stat shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-dark p-3 rounded-3 me-3"><i class="bi bi-basket fs-3"></i></div>
                    <div><p class="text-muted mb-0 small">Order Aktif</p><h4 class="fw-bold mb-0"><?= $total_order; ?></h4></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card card-stat shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-3 me-3"><i class="bi bi-motorcycle fs-3"></i></div>
                    <div><p class="text-muted mb-0 small">Kurir Ready</p><h4 class="fw-bold mb-0"><?= $kurir_standby; ?></h4></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-navy mb-0 h6-md">Monitoring Transaksi</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="location.reload()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="ps-4">Pelanggan</th>
                                    <th>Layanan & Berat</th>
                                    <th>Pembayaran</th>
                                    <th>Lokasi</th>
                                    <th>Kurir</th>
                                    <th>Status Order</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query = mysqli_query($koneksi, "SELECT pesanan.*, user.nama as nama_pelanggan, user.is_member, layanan.nama_layanan, kurir.nama_kurir 
                                                                 FROM pesanan 
                                                                 JOIN user ON pesanan.user_id = user.id 
                                                                 JOIN layanan ON pesanan.layanan_id = layanan.id 
                                                                 LEFT JOIN kurir ON pesanan.kurir_id = kurir.id 
                                                                 ORDER BY pesanan.id DESC");
                                while($row = mysqli_fetch_array($query)): 
                                ?>
                                <tr class="<?= ($row['status']=='Pending') ? 'table-warning' : ''; ?>">
                                    <td class="ps-4">
                                        <div class="fw-bold"><?= $row['nama_pelanggan']; ?></div>
                                        <?= ($row['is_member']) ? '<span class="badge bg-warning text-dark" style="font-size: 10px;">VIP</span>' : '<span class="badge bg-light text-secondary border" style="font-size: 10px;">Regular</span>'; ?>
                                    </td>
                                    <td>
                                        <div class="small fw-bold"><?= $row['nama_layanan']; ?></div>
                                        <div class="text-muted small"><?= $row['berat']; ?> Kg</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">Rp <?= number_format($row['total_bayar']); ?></div>
                                        <div class="small mb-1 text-muted"><?= $row['metode_bayar']; ?></div>
                                        
                                        <?php if($row['status_bayar'] == 'Lunas'): ?>
                                            <span class="badge bg-success">Lunas</span>
                                        <?php elseif($row['status_bayar'] == 'Menunggu Verifikasi'): ?>
                                            <span class="badge bg-warning text-dark blink">Cek Bukti</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Belum Bayar</span>
                                        <?php endif; ?>

                                        <?php if($row['bukti_bayar']): ?>
                                            <a href="../assets/images/bukti_bayar/<?= $row['bukti_bayar']; ?>" target="_blank" class="d-block mt-1 small text-primary text-decoration-underline">Lihat Bukti</a>
                                            <?php if($row['status_bayar'] != 'Lunas'): ?>
                                            <form method="POST" class="mt-1">
                                                <input type="hidden" name="id_pesanan" value="<?= $row['id']; ?>">
                                                <button type="submit" name="verifikasi_bayar" class="btn btn-success btn-sm py-0" style="font-size: 10px;">ACC</button>
                                            </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($row['link_maps']): ?>
                                            <a href="<?= $row['link_maps']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="bi bi-geo-alt-fill"></i></a>
                                        <?php else: ?> - <?php endif; ?>
                                    </td>
                                    
                                    <form method="POST">
                                        <input type="hidden" name="id_pesanan" value="<?= $row['id']; ?>">
                                        <td>
                                            <select name="kurir_id" class="form-select form-select-sm" style="width: 120px;">
                                                <option value="">- Pilih -</option>
                                                <?php 
                                                $qKurir = mysqli_query($koneksi, "SELECT * FROM kurir");
                                                while($k = mysqli_fetch_array($qKurir)){
                                                    $sel = ($k['id'] == $row['kurir_id']) ? 'selected' : '';
                                                    echo "<option value='$k[id]' $sel>$k[nama_kurir]</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="status" class="form-select form-select-sm fw-bold <?= ($row['status']=='Selesai')?'text-success':'text-warning'; ?>" style="width: 110px;">
                                                <option value="Pending" <?= ($row['status']=='Pending')?'selected':''; ?>>Pending</option>
                                                <option value="Dijemput" <?= ($row['status']=='Dijemput')?'selected':''; ?>>Dijemput</option>
                                                <option value="Dicuci" <?= ($row['status']=='Dicuci')?'selected':''; ?>>Dicuci</option>
                                                <option value="Diantar" <?= ($row['status']=='Diantar')?'selected':''; ?>>Diantar</option>
                                                <option value="Selesai" <?= ($row['status']=='Selesai')?'selected':''; ?>>Selesai</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="update_order" value="1">
                                            <button type="submit" class="btn btn-primary-custom btn-sm rounded-circle shadow-sm" title="Simpan"><i class="bi bi-check-lg"></i></button>
                                            <a href="cetak_struk.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-dark btn-sm rounded-circle shadow-sm ms-1" title="Cetak"><i class="bi bi-printer"></i></a>
                                        </td>
                                    </form>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3"><h6 class="fw-bold text-navy mb-0">Kurir Standby</h6></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php 
                        $kurirList = mysqli_query($koneksi, "SELECT * FROM kurir");
                        while($kurir = mysqli_fetch_array($kurirList)):
                            $badge = ($kurir['status'] == 'Standby') ? 'bg-success' : 'bg-secondary';
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div><span class="fw-bold d-block"><?= $kurir['nama_kurir']; ?></span><span class="badge <?= $badge; ?> rounded-pill" style="font-size: 10px;"><?= $kurir['status']; ?></span></div>
                            <a href="https://wa.me/<?= $kurir['no_hp']; ?>" target="_blank" class="btn btn-sm btn-success rounded-circle"><i class="bi bi-whatsapp"></i></a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                    <div class="d-grid mt-3"><a href="data_kurir.php" class="btn btn-outline-primary btn-sm w-100">Kelola Data Kurir</a></div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3"><h6 class="fw-bold text-navy mb-0">Pelanggan Baru</h6></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php 
                        $pelangganBaru = mysqli_query($koneksi, "SELECT * FROM user WHERE level='pelanggan' ORDER BY id DESC LIMIT 5");
                        while($p = mysqli_fetch_array($pelangganBaru)):
                        ?>
                        <li class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-2 text-secondary"><i class="bi bi-person"></i></div>
                                <div>
                                    <span class="fw-bold d-block small"><?= $p['nama']; ?></span>
                                    <span class="text-muted small" style="font-size: 11px;">
                                        <?= ($p['is_member']) ? '<i class="bi bi-star-fill text-warning"></i> Member' : 'Regular'; ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>