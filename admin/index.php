<?php

if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])){ header("Location: ../login.php");exit;}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman Administrator</title>
    <link rel="stylesheet" type="text/css" href="../assets/images/style.css">
</head>
<body>
<div class="wrap">
      <div class="header">
        <a href="../index.php"><img src="../assets/images/logo.png" alt="Logo"></a>     
      </div>
    
      <div class="page">
          <div class="p-left">
              <div class="box">
                  <h1>Menu Utama</h1>
                  <ul class="nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="anggota">Data Anggota</a></li>
                    <li><a href="barang/">Data Barang</a></li>
                    <li><a href="perusahaan/">Data Perusahaan</a></li>
                    <li><a href="bongkar-muat/">Data Bongkar Muat</a></li>
                    <li><a href="../logout.php">Logout Now</a>
                  </ul>
              </div>
          </div>
          <div class="p-right">
                <div class="box">
                    <h1>Selamat Datang di Program Pengolahan Bongkar Muat di PT XYZ</h1>
                    <p>Berikut ini adalah alur dalam penggunaan program ini:</p>
                    <ul class="fitur">
                        <li><a href="anggota/tambah.php">Membuat data anggota</a></li>
                        <li><a href="barang/tambah.php">Membuat data barang</a></li>
                        <li><a href="perusahaan/tambah.php">Membuat data perusahaan</a></li>
                        <li><a href="bongkar-muat/tambah.php">Membuat data bongkar-muat</a></li>
                    </ul>
                </div>
          </div>
          <div class="clear"></div>
      </div>
      <div class="footer">
          All Rights Reserved | Copyright &copy; - 2014 | Program Pengolahan Bongkar Muat
      </div>
    </div>
</body>
</html>