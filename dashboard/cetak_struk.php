<?php 
include '../koneksi.php';
$id = $_GET['id'];

// Ambil detail pesanan
$query = mysqli_query($koneksi, "SELECT pesanan.*, user.nama, layanan.nama_layanan, layanan.harga_per_kg 
                                 FROM pesanan 
                                 JOIN user ON pesanan.user_id = user.id 
                                 JOIN layanan ON pesanan.layanan_id = layanan.id 
                                 WHERE pesanan.id='$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk #<?= $id; ?></title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 14px; max-width: 300px; margin: 0 auto; padding: 10px; }
        .text-center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        .flex { display: flex; justify-content: space-between; }
        .bold { font-weight: bold; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <h3 style="margin-bottom: 5px;">JLF & CO LAUNDRY</h3>
        <small>Jl. Susuk II, Medan</small><br>
        <small>WA: 0831-2493-2816</small>
    </div>

    <div class="line"></div>

    <div class="flex">
        <span>Nota: #ORD-<?= $data['id']; ?></span>
        <span><?= date('d/m/y H:i', strtotime($data['tanggal_pesan'])); ?></span>
    </div>
    <div class="flex">
        <span>Plg: <?= $data['nama']; ?></span>
    </div>

    <div class="line"></div>

    <div>
        <div class="bold"><?= $data['nama_layanan']; ?></div>
        <div class="flex">
            <span><?= $data['berat']; ?> Kg x <?= number_format($data['harga_per_kg']); ?></span>
            <span><?= number_format($data['berat'] * $data['harga_per_kg']); ?></span>
        </div>
    </div>

    <?php 
    $total_normal = $data['berat'] * $data['harga_per_kg'];
    $selisih = $total_normal - $data['total_bayar']; 

    if($selisih > 0): 
    ?>
    <div class="flex" style="margin-top: 5px;">
        <span>Diskon Member / Promo</span>
        <span>-<?= number_format($selisih); ?></span>
    </div>
    <?php endif; ?>

    <div class="line"></div>

    <div class="flex bold" style="font-size: 16px;">
        <span>TOTAL BAYAR</span>
        <span>Rp <?= number_format($data['total_bayar']); ?></span>
    </div>
    <div class="flex bold" style="font-size: 16px;">
        <span>TOTAL BAYAR</span>
        <span>Rp <?= number_format($data['total_bayar']); ?></span>
    </div>

    <div class="line"></div>
    <div class="flex" style="margin-top: 5px;">
        <span>Status Pembayaran:</span>
        <span style="font-weight: bold; text-transform: uppercase;">
            <?= $data['status_bayar']; ?> 
            <?= ($data['status_bayar'] == 'Lunas') ? '(LUNAS)' : ''; ?>
        </span>
    </div>
    <div class="flex">
        <span>Metode:</span>
        <span><?= $data['metode_bayar']; ?></span>
    </div>
    <div class="line"></div>

    <div class="line"></div>
    
    <div class="text-center" style="margin-top: 20px;">
        <small>Terima Kasih Atas Kepercayaan Anda</small><br>
        <small>Barang yang tidak diambil > 1 bulan<br>bukan tanggung jawab kami.</small>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <a href="halaman_admin.php" style="text-decoration: none; background: #eee; padding: 10px; border-radius: 5px;">&laquo; Kembali</a>
    </div>

</body>
</html>