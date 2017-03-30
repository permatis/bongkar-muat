<?php

if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])) header("Location: index.php");

?>
<div class="p-left">
    <div class="box">
        <h1>Menu Utama</h1>
        <ul class="nav">
          <li><a href="../">Home</a></li>
          <li><a href="../anggota">Data Anggota</a></li>
          <li><a href="../barang/">Data Barang</a></li>
          <li><a href="../perusahaan/">Data Perusahaan</a></li>
          <li><a href="../bongkar-muat/">Data Bongkar Muat</a></li>
          <li><a href="../../logout.php">Logout Now</a>
        </ul>
    </div>
</div>