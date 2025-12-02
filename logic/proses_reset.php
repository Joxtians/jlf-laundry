<?php 
session_start();
include '../koneksi.php';

if(isset($_POST['cek_user'])){
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];

   
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email' AND no_hp='$no_hp'");
    
    if(mysqli_num_rows($query) > 0){
       
        $data = mysqli_fetch_assoc($query);
        $_SESSION['reset_id_user'] = $data['id'];
        
        
        header("location:../reset_password_form.php");
    } else {
       
        header("location:../lupa_password.php?pesan=gagal");
    }
}


if(isset($_POST['simpan_password'])){
    $pass_baru = $_POST['pass_baru'];
    $pass_konfirm = $_POST['pass_konfirm'];
    $id_user = $_SESSION['reset_id_user'];

    if($pass_baru == $pass_konfirm){
       
        $update = mysqli_query($koneksi, "UPDATE user SET password='$pass_baru' WHERE id='$id_user'");
        
        if($update){
           
            unset($_SESSION['reset_id_user']);
            
            
            echo "<script>
                    alert('Password berhasil diubah! Silakan Login.');
                    window.location='../login.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Password tidak cocok! Silakan coba lagi.');
                window.location='../reset_password_form.php';
              </script>";
    }
}
?>