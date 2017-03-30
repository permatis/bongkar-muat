<?php 
include_once('lib/Database.php');

if(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)){
   //filter setiap inputan
   $id         = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
   
   //menampilkan data anggota berdasarkan anggota id.
   $anggota    = Database::get('anggota')->where('id_anggota', '=', $id)->result();

   if(empty($anggota)) header("Location: index.php");

   //menampilkan data perusahaan berdasarkan anggota_id dari table relasi_anggota;
   $q_perusahaan      = "
      SELECT
         b.tanggal,
         p.nama,
      b.`status`,
         SUM(
            b.total_harga DIV jml_anggota
         ) AS pendapatan
      FROM
         bongkarmuat b
      INNER JOIN relasi_anggota r ON b.id_bongkarmuat = r.bongkarmuat_id
      INNER JOIN (
         SELECT
            bongkarmuat_id,
            COUNT(anggota_id) + 1 jml_anggota
         FROM
            relasi_anggota
         GROUP BY
            bongkarmuat_id
      ) c ON c.bongkarmuat_id = b.id_bongkarmuat
      INNER JOIN anggota a ON r.anggota_id = a.id_anggota
      LEFT JOIN perusahaan p ON b.perusahaan_id = p.id_perusahaan
      WHERE r.anggota_id = ".$id."
      GROUP BY
         b.status
   ";

   $perusahaan = Database::query($q_perusahaan)->result();

   //menampilkan keseluruhan gaji anggota
   $q_total = "
       SELECT
          SUM(
              b.total_harga * 1.0 DIV jml_anggota
          ) AS pendapatan
      FROM
          bongkarmuat b
      INNER JOIN relasi_anggota r ON b.id_bongkarmuat = r.bongkarmuat_id
      INNER JOIN (
          SELECT
              bongkarmuat_id,
              COUNT(anggota_id)+1 jml_anggota
          FROM
              relasi_anggota
          GROUP BY
              bongkarmuat_id
      ) c ON c.bongkarmuat_id = b.id_bongkarmuat
      INNER JOIN anggota a ON r.anggota_id = a.id_anggota
        LEFT JOIN perusahaan p ON b.perusahaan_id = p.id_perusahaan
      WHERE r.anggota_id = ".$id."
      GROUP BY
         a.nama
   ";

   $total_gaji = Database::query($q_total)->result(); 

 }else{
   header("Location: index.php");
 }
?>


<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Detail Gaji Anggota</title>
      <link rel="stylesheet" type="text/css" href="assets/images/style.css">
   </head>
   <body>
      <div class="wrap">
         <div class="header">
            <a href="index.php"><img src="assets/images/logo.png" alt="Logo"></a>
         </div>
         <div class="page">
            <div class="box">
               <h1>Data Transfer Gaji</h1>
               <p>Berikut ini adalah detail gaji harian untuk anggota:</p>
               <table border="0">
                  <tr class="data">
                     <td>Tanggal Kerja</td>
                     <td colspan="2"><b>: &nbsp; <?php echo Database::tglIndonesia($perusahaan[0]['tanggal']); ?></td>
                  </tr>
                  <tr class="data">
                     <td colspan="3"></td>
                  </tr>
                  <tr class="data">
                     <td colspan="3"><b>A. Informasi Anggota</b></td>
                  </tr>
                  <tr class="data">
                     <td>NIK</td>
                     <td><b>: &nbsp; <?php echo $anggota[0]['nik']; ?></b></td>
                     <td width="300"></td>
                  </tr>
                  <tr class="data">
                     <td>Nama Anggota</td>
                     <td><b>: &nbsp; <?php echo $anggota[0]['nama']; ?></b></td>
                     <td width="300"></td>
                  </tr>
                  <tr class="data">
                     <td>Nomor HP</td>
                     <td><b>: &nbsp; <?php echo $anggota[0]['no_hp']; ?></b></td>
                     <td width="300"></td>
                  </tr>
                  <tr class="data">
                     <td>Alamat</td>
                     <td><b>: &nbsp; <?php echo $anggota[0]['alamat']; ?></b></td>
                     <td width="300"></td>
                  </tr>
                  <tr class="data">
                     <td colspan="3"></td>
                  </tr>
                  <tr class="data">
                     <td colspan="3"><b>B. Informasi Pekerjaan</b></td>
                  </tr>
                  <?php foreach($perusahaan as $per): ?>
                  <tr class="data">
                     <td>Nama Perusahaan</td>
                     <td><b>: &nbsp; <?php echo $per['nama']; ?></b></td>
                     <td width="300"></td>
                  </tr>
                  <tr class="data">
                     <td>Status </td>
                     <td><b>: &nbsp; <?php echo $per['status']; ?></b></td>
                     <td width="300"></td>
                  </tr>
                  <tr class="data">
                     <td>Pendapatan </td>
                     <td><b>: &nbsp; <?php echo "Rp. ".number_format($per['pendapatan'], 0, '', '.'); ?></b></td>
                     <td width="300"></td>
                  </tr>
                  <?php endforeach; ?>
                  <tr class="data">
                     <td colspan="3"></td>
                  </tr>
                  <tr class="data">
                     <td colspan="3"><b>C. Informasi Gaji</b></td>
                  </tr>
                  <tr class="data">
                     <td>Total Gaji</td>
                     <td><b>: &nbsp; <?php echo "Rp. ".number_format($total_gaji[0]['pendapatan'], 0, '', '.'); ?></b></td>
                     <td width="300"></td>
                  </tr>
               </table>
               <p>
               <a href="index.php">Klik untuk kembali</a>
               <a href="laporan/lap_gaji.php?id=<?php echo $id;?>" class="right">
                  <img src="assets/images/printer.png">
                  <label class="tambah">cetak</label>
               </a>
               </p>
            </div>
            <div class="clear"></div>
         </div>
         <div class="footer">
            All Rights Reserved | Copyright &copy; - 2014 | Program Pengolahan Bongkar Muat
         </div>
      </div>
   </body>
</html>