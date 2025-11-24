<?php 
include '../koneksi.php';

$nama = $_POST['nama'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp']; 
$password = $_POST['password'];


$level = 'pelanggan'; 
$is_member = 0; 

$username = $email; 

$cek_email = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
if(mysqli_num_rows($cek_email) > 0){
    header("location:../register.php?pesan=gagal"); 
    exit;
}


$query = "INSERT INTO user (nama, email, no_hp, username, password, level, is_member) 
          VALUES ('$nama', '$email', '$no_hp', '$username', '$password', '$level', '$is_member')";

$input = mysqli_query($koneksi, $query);

if($input){
    header("location:../login.php?pesan=register&email=$email");
}else{
    echo "Gagal Daftar: " . mysqli_error($koneksi);
}
?>