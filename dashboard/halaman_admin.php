<?php 
session_start();
include '../koneksi.php';


if(!isset($_SESSION['level']) || $_SESSION['level'] != "admin"){
    header("location:../index.php?pesan=gagal");
    exit;
}

$pageTitle = "Laman Admin - JLF Laundry";


if(isset($_POST['update_order'])){
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status'];
    $kurir_baru = $_POST['kurir_id'];
    
    
    $update = mysqli_query($koneksi, "UPDATE pesanan SET status='$status_baru', kurir_id='$kurir_baru' WHERE id='$id_pesanan'");
    
    
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
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .card-stat { transition: all 0.3s; border:none; }
        .card-stat:hover { transform: translateY(-5px); }
        .bg-navy { background-color: var(--primary-color); }
        .text-navy { color: var(--primary-color); }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-navy sticky-top shadow">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="#">LAMAN ADMIN</a>
        <div class="ms-auto d-flex align-items-center text-white">
            <span class="me-3"><i class="bi bi-person-circle me-2"></i><?= $_SESSION['nama']; ?></span>
            <a href="../logic/logout.php" class="btn btn-sm btn-danger fw-bold">Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 py-4">
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-stat shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary-custom text-white p-3 rounded-3 me-3">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Total Pendapatan</p>
                        <h4 class="fw-bold mb-0">Rp <?= number_format($total_income); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-dark p-3 rounded-3 me-3">
                        <i class="bi bi-basket fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Orderan Aktif</p>
                        <h4 class="fw-bold mb-0"><?= $total_order; ?> Pesanan</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-3 me-3">
                        <i class="bi bi-motorcycle fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Kurir Standby</p>
                        <h4 class="fw-bold mb-0"><?= $kurir_standby; ?> Orang</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-navy mb-0">Monitoring Transaksi Realtime</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="location.reload()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh Data</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
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
                                        <?php if($row['is_member']): ?>
                                            <span class="badge bg-warning text-dark" style="font-size: 10px;">VIP MEMBER</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border" style="font-size: 10px;">Regular</span>
                                        <?php endif; ?>
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
                                                <button type="submit" name="verifikasi_bayar" class="btn btn-success btn-sm py-0" style="font-size: 10px;">ACC Lunas</button>
                                            </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if($row['link_maps']): ?>
                                            <a href="<?= $row['link_maps']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" title="Lihat Lokasi">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <form method="POST">
                                        <input type="hidden" name="id_pesanan" value="<?= $row['id']; ?>">
                                        <td>
                                            <select name="kurir_id" class="form-select form-select-sm" style="width: 130px;">
                                                <option value="">- Pilih -</option>
                                                <?php 
                                                $qKurir = mysqli_query($koneksi, "SELECT * FROM kurir");
                                                while($k = mysqli_fetch_array($qKurir)){
                                                    $selected = ($k['id'] == $row['kurir_id']) ? 'selected' : '';
                                                    echo "<option value='$k[id]' $selected>$k[nama_kurir]</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="status" class="form-select form-select-sm fw-bold 
                                                <?= ($row['status']=='Pending')?'text-warning':''; ?>
                                                <?= ($row['status']=='Selesai')?'text-success':''; ?>" 
                                                style="width: 120px;">
                                                
                                                <option value="Pending" <?= ($row['status']=='Pending')?'selected':''; ?>>Pending</option>
                                                <option value="Dijemput" <?= ($row['status']=='Dijemput')?'selected':''; ?>>Dijemput</option>
                                                <option value="Dicuci" <?= ($row['status']=='Dicuci')?'selected':''; ?>>Dicuci</option>
                                                <option value="Diantar" <?= ($row['status']=='Diantar')?'selected':''; ?>>Diantar</option>
                                                <option value="Selesai" <?= ($row['status']=='Selesai')?'selected':''; ?>>Selesai</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="update_order" value="1">
                                            
                                            <button type="submit" class="btn btn-primary-custom rounded-circle shadow-sm" style="width: 35px; height: 35px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Simpan Perubahan">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            
                                            <a href="cetak_struk.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-dark rounded-circle shadow-sm ms-1" style="width: 35px; height: 35px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Cetak Struk">
                                                <i class="bi bi-printer"></i>
                                            </a>
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
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-navy mb-0">Kurir Standby</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php 
                        $kurirList = mysqli_query($koneksi, "SELECT * FROM kurir");
                        while($kurir = mysqli_fetch_array($kurirList)):
                            $badgeStatus = ($kurir['status'] == 'Standby') ? 'bg-success' : 'bg-secondary';
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <span class="fw-bold d-block"><?= $kurir['nama_kurir']; ?></span>
                                <span class="badge <?= $badgeStatus; ?> rounded-pill" style="font-size: 10px;"><?= $kurir['status']; ?></span>
                            </div>
                            <a href="https://wa.me/<?= $kurir['no_hp']; ?>" target="_blank" class="btn btn-sm btn-success rounded-circle">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                    <div class="d-grid mt-3">
                        <a href="data_kurir.php" class="btn btn-outline-primary btn-sm w-100">Kelola Data Kurir</a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-navy mb-0">Pelanggan Baru</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php 
                        $pelangganBaru = mysqli_query($koneksi, "SELECT * FROM user WHERE level='pelanggan' ORDER BY id DESC LIMIT 5");
                        while($p = mysqli_fetch_array($pelangganBaru)):
                        ?>
                        <li class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-2 text-secondary">
                                    <i class="bi bi-person"></i>
                                </div>
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