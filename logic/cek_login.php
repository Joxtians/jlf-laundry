<?php 
session_start();
include '../koneksi.php'; 

$username = $_POST['username'];
$password = $_POST['password'];
 
$login = mysqli_query($koneksi,"SELECT * FROM user WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($login);
 
if($cek > 0){
    $data = mysqli_fetch_assoc($login);
 
    if($data['level']=="admin"){
        $_SESSION['username'] = $username;
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['level'] = "admin";
        $_SESSION['id_user'] = $data['id']; 
        
        header("location:../dashboard/halaman_admin.php");
 
    }else if($data['level']=="pelanggan"){
        $_SESSION['username'] = $username;
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['level'] = "pelanggan";
        $_SESSION['id_user'] = $data['id']; 
 
        header("location:../dashboard/halaman_pelanggan.php");
 
    }else{
        
        header("location:../login.php?pesan=gagal");
    }   
}else{
    
    header("location:../login.php?pesan=gagal");
}
?>