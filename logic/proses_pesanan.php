<?php 
include '../koneksi.php';


$user_id = $_POST['user_id'];
$layanan_id = $_POST['layanan_id'];
$berat = $_POST['berat'];
$alamat = $_POST['alamat'];
$link_maps = $_POST['link_maps'];
$is_member = $_POST['is_member'];
$voucher_claimed_id = $_POST['voucher_claimed_id'];

$metode_bayar = isset($_POST['metode_bayar']) ? $_POST['metode_bayar'] : 'Cash';


$qLayanan = mysqli_query($koneksi, "SELECT harga_per_kg FROM layanan WHERE id='$layanan_id'");
$dLayanan = mysqli_fetch_assoc($qLayanan);
$harga_per_kg = $dLayanan['harga_per_kg'];

$total_awal = $harga_per_kg * $berat;
$ongkir = ($is_member == 1) ? 0 : 10000; 
$diskon_member = ($is_member == 1) ? ($total_awal * 0.10) : 0; 

$potongan_voucher = 0;

if(!empty($voucher_claimed_id)){
    
    $qCekV = mysqli_query($koneksi, "SELECT v.* FROM user_vouchers uv JOIN vouchers v ON uv.voucher_id = v.id WHERE uv.id = '$voucher_claimed_id'");
    $dV = mysqli_fetch_assoc($qCekV);

    
    if($total_awal >= $dV['min_belanja']){
        $potongan_voucher = $dV['potongan'];
        
        mysqli_query($koneksi, "UPDATE user_vouchers SET status='used' WHERE id='$voucher_claimed_id'");
    }
}


$grand_total = ($total_awal - $diskon_member - $potongan_voucher) + $ongkir;
if($grand_total < 0) $grand_total = 0;


$kode_bayar = "PAY-" . rand(100000, 999999);


$query = "INSERT INTO pesanan (user_id, layanan_id, berat, alamat_jemput, link_maps, total_bayar, potongan_voucher, kode_pembayaran, status, tanggal_pesan, kurir_id, metode_bayar, status_bayar) 
          VALUES (
            '$user_id', 
            '$layanan_id', 
            '$berat', 
            '$alamat', 
            '$link_maps', 
            '$grand_total', 
            '$potongan_voucher', 
            '$kode_bayar', 
            'Pending', 
            NOW(), 
            NULL, 
            '$metode_bayar',  
            'Belum Lunas'
          )";

if(mysqli_query($koneksi, $query)){
    $last_id = mysqli_insert_id($koneksi);
    header("location:../dashboard/detail_pesanan.php?id=$last_id");
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>