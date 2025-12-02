<?php 
session_start();
include '../koneksi.php';

if(isset($_POST['kirim_ulasan'])){
    $id_pesanan = $_POST['id_pesanan'];
    $user_id = $_SESSION['id_user']; 
    $komentar = $_POST['komentar'];

    
    $query = "INSERT INTO ulasan (id_pesanan, user_id, rating, komentar) VALUES ('$id_pesanan', '$user_id', '$rating', '$komentar')";
    
    if(mysqli_query($koneksi, $query)){
        echo "<script>alert('Terima kasih atas penilaian Anda!'); window.location='../dashboard/halaman_pelanggan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>