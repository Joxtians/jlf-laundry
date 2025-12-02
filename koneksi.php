<?php 
$host = "localhost";
$user = "root";
$pass = "";
$db   = "laundry_db"; // HARUS SAMA dengan nama database di atas

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
?>