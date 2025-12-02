<?php 
include '../koneksi.php';

$user_id = $_POST['user_id'];


$upgrade = mysqli_query($koneksi, "UPDATE user SET is_member = 1 WHERE id = '$user_id'");

if($upgrade){
    echo "<script>
            alert('Selamat! Anda sekarang adalah Member VIP. Nikmati diskon dan gratis ongkir.');
            window.location.href='../dashboard/halaman_pelanggan.php';
          </script>";
} else {
    echo "Gagal upgrade.";
}
?>