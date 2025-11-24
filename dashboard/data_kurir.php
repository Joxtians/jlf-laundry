<?php 
session_start();
include '../koneksi.php';

// Cek Admin
if(!isset($_SESSION['level']) || $_SESSION['level'] != "admin"){
    header("location:../index.php");
    exit;
}

// Proses Tambah Kurir
if(isset($_POST['tambah_kurir'])){
    $nama = $_POST['nama'];
    $hp = $_POST['hp'];
    mysqli_query($koneksi, "INSERT INTO kurir VALUES(NULL, '$nama', '$hp', 'Standby')");
    header("Refresh:0");
}

// Proses Hapus
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM kurir WHERE id='$id'");
    header("location:data_kurir.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Kurir - JLF Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between mb-4">
                <h3>Manajemen Kurir</h3>
                <a href="halaman_admin.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="POST" class="d-flex gap-2">
                        <input type="text" name="nama" class="form-control" placeholder="Nama Kurir" required>
                        <input type="text" name="hp" class="form-control" placeholder="No HP (628...)" required>
                        <button type="submit" name="tambah_kurir" class="btn btn-primary-custom">Tambah</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kurir</th>
                            <th>No WhatsApp</th>
                            <th>Status Saat Ini</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $data = mysqli_query($koneksi, "SELECT * FROM kurir");
                        while($k = mysqli_fetch_array($data)):
                        ?>
                        <tr>
                            <td><?= $k['nama_kurir']; ?></td>
                            <td><?= $k['no_hp']; ?></td>
                            <td>
                                <span class="badge <?= ($k['status']=='Standby')?'bg-success':'bg-secondary'; ?>">
                                    <?= $k['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="data_kurir.php?hapus=<?= $k['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kurir ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>