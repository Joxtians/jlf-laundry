<?php 
session_start();
include '../koneksi.php';

// Cek keamanan
if(!isset($_SESSION['level']) || $_SESSION['level'] != "pelanggan"){
    header("location:../index.php?pesan=gagal");
    exit;
}

// Ambil data user & Status Member
$username = $_SESSION['username'];
$queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
$userData = mysqli_fetch_assoc($queryUser);
$isMember = $userData['is_member'];
$id_user = $userData['id'];

$pageTitle = "Dashboard Pelanggan - JLF & CO";
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
        .rating { display: flex; flex-direction: row-reverse; justify-content: center; }
        .rating input { display: none; }
        .rating label { font-size: 2rem; color: #ddd; cursor: pointer; padding: 0 5px; }
        .rating input:checked ~ label { color: #ffc107; }
        .rating label:hover, .rating label:hover ~ label { color: #ffdb70; }
        
        /* Style Khusus Info Kurir */
        .courier-info { font-size: 0.85rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 5px 10px; border-radius: 20px; display: inline-flex; align-items: center; margin-top: 5px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary-custom" href="#">JLF & CO</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <?php if($isMember): ?>
                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> MEMBER VIP</span>
            <?php else: ?>
                <span class="badge bg-secondary">Pelanggan Biasa</span>
            <?php endif; ?>
            <span class="fw-bold d-none d-md-block"><?= $_SESSION['nama']; ?></span>
            <a href="../logic/logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    
    <?php if(!$isMember): ?>
    <div class="card border-warning mb-5 shadow-sm" style="background: linear-gradient(to right, #fffbeb, #fff);">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-bold text-warning"><i class="bi bi-crown me-2"></i>Upgrade ke Member VIP!</h4>
                <p class="mb-0 text-muted">Hanya <strong>Rp 25.000/bulan</strong>. Diskon Tiap Transaksi & Gratis Ongkir!</p>
            </div>
            <form action="../logic/proses_upgrade.php" method="POST">
                <input type="hidden" name="user_id" value="<?= $id_user; ?>">
                <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm" onclick="return confirm('Bayar Rp 25.000 untuk jadi member?')">UPGRADE SEKARANG</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-primary-custom">Riwayat Cucian</h2>
            <p class="text-muted">Pantau status & info kurir Anda</p>
        </div>
        <a href="pesan_laundry.php" class="btn btn-primary-custom shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Buat Pesanan
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 ps-4">Layanan & Info</th>
                        <th>Tgl Pesan</th>
                        <th>Status Pesanan</th> <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // UPDATE QUERY: JOIN DENGAN TABEL KURIR
                    $queryPesanan = mysqli_query($koneksi, "SELECT p.*, l.nama_layanan, u.rating, u.komentar, k.nama_kurir, k.no_hp 
                                                            FROM pesanan p 
                                                            JOIN layanan l ON p.layanan_id = l.id 
                                                            LEFT JOIN ulasan u ON p.id = u.id_pesanan 
                                                            LEFT JOIN kurir k ON p.kurir_id = k.id 
                                                            WHERE p.user_id='$id_user' 
                                                            ORDER BY p.id DESC");
                    
                    if(mysqli_num_rows($queryPesanan) > 0){
                        while($row = mysqli_fetch_assoc($queryPesanan)){
                            $badgeColor = 'bg-secondary';
                            if($row['status'] == 'Dijemput') $badgeColor = 'bg-info';
                            if($row['status'] == 'Dicuci') $badgeColor = 'bg-primary';
                            if($row['status'] == 'Diantar') $badgeColor = 'bg-warning text-dark';
                            if($row['status'] == 'Selesai') $badgeColor = 'bg-success';
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-primary-custom"><?= $row['nama_layanan']; ?></div>
                                    <small class="text-muted d-block">Nota: #<?= $row['kode_pembayaran']; ?></small>
                                    <a href="detail_pesanan.php?id=<?= $row['id']; ?>" class="small text-decoration-none">Lihat Detail</a>
                                </td>
                                <td><?= date('d M Y', strtotime($row['tanggal_pesan'])); ?></td>
                                
                                <td>
                                    <span class="badge <?= $badgeColor; ?> mb-1"><?= $row['status']; ?></span>
                                    
                                    <?php if(($row['status'] == 'Dijemput' || $row['status'] == 'Diantar') && $row['nama_kurir']): ?>
                                        <br>
                                        <div class="courier-info">
                                            <i class="bi bi-motorcycle me-2"></i>
                                            <span><?= $row['nama_kurir']; ?></span>
                                            <a href="https://wa.me/<?= $row['no_hp']; ?>" target="_blank" class="ms-2 text-success fw-bold text-decoration-none">
                                                <i class="bi bi-whatsapp"></i> Hubungi
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="fw-bold text-success mt-1">Rp <?= number_format($row['total_bayar']); ?></div>
                                </td>
                                
                                <td>
                                    <?php if($row['status'] == 'Selesai'): ?>
                                        <?php if($row['rating']): ?>
                                            <div class="text-warning">
                                                <?php for($i=0; $i<$row['rating']; $i++) echo '<i class="bi bi-star-fill"></i>'; ?>
                                            </div>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-warning fw-bold" onclick="bukaModalNilai('<?= $row['id']; ?>')">
                                                <i class="bi bi-star me-1"></i>Beri Nilai
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">...</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center py-5 text-muted">Belum ada pesanan.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRating" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Beri Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <form action="../logic/simpan_ulasan.php" method="POST">
                    <input type="hidden" name="id_pesanan" id="input_id_pesanan">
                    <p class="text-muted">Seberapa puas Anda dengan layanan kami?</p>
                    <div class="rating mb-3">
                        <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
                        <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                        <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                        <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                        <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                    </div>
                    <div class="mb-3">
                        <textarea name="komentar" class="form-control bg-light" rows="3" placeholder="Tulis komentar Anda di sini..." required></textarea>
                    </div>
                    <button type="submit" name="kirim_ulasan" class="btn btn-primary-custom w-100 fw-bold">Kirim Penilaian</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function bukaModalNilai(id) {
        document.getElementById('input_id_pesanan').value = id;
        var myModal = new bootstrap.Modal(document.getElementById('modalRating'));
        myModal.show();
    }
</script>

</body>
</html>