<?php 

require_once( '../lib/Database.php'); 
require_once(dirname(__FILE__). '/html2pdf/html2pdf.class.php'); 

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

ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Gaji Anggota</title>
    <style type="text/css">
        body {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .tgl {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border: 1px solid #000;
        }
        td {
            height: 10px;
            padding: 5px 10px 5px 10px;
        }
        .tb_informasi {
            font-size: 14px;
            font-weight: bold;
        }
        .ket-ttd,  .nama-ttd {
            text-align: right;
        }
        .ket-ttd{
            margin-top: 50px;
            margin-right: 60px;
        }
        .nama-ttd{
            margin-right: 80px;
        }
        img {
            height: 150px;
            position: relative;
            float: right;
        }
        .nama-ttd {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center">SLIP GAJI ANGGOTA</h2>
    <div class="tgl">Tanggal Kerja : <b><?php echo Database::tglIndonesia($perusahaan[0]['tanggal']); ?></b>
    </div>
    <table>
        <tr>
            <td colspan="2" class="tb_informasi">A. Informasi Anggota</td>
        </tr>
        <tr>
            <td width="400">NIK</td>
            <td width="265"><b>: &nbsp; <?php echo $anggota[0]['nik']; ?></b></td>
        </tr>
        <tr>
            <td>Nama Anggota</td>
            <td><b>: &nbsp; <?php echo $anggota[0]['nama']; ?></b></td>
        </tr>
        <tr>
            <td>Nomor HP</td>
            <td><b>: &nbsp; <?php echo $anggota[0]['no_hp']; ?></b></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td><b>: &nbsp; <?php echo $anggota[0]['alamat']; ?></b></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2" class="tb_informasi">B. Informasi Pekerjaan</td>
        </tr>

        <?php foreach($perusahaan as $per): ?>
        <tr>
            <td>Nama Perusahaan</td>
            <td><b>: &nbsp; <?php echo $per['nama']; ?></b></td>
        </tr>
        <tr>
            <td>Status </td>
            <td><b>: &nbsp; <?php echo $per['status']; ?></b></td>
        </tr>
        <tr>
            <td>Pendapatan </td>
            <td><b>: &nbsp; <?php echo "Rp. ".number_format($per['pendapatan'], 0, '', '.'); ?></b></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2" class="tb_informasi">C. Informasi Gaji</td>
        </tr>
        <tr>
            <td>Total Gaji</td>
            <td><b>: &nbsp; <?php echo "Rp. ".number_format($total_gaji[0]['pendapatan'], 0, '', '.'); ?></b></td>
        </tr>
    </table>
    <div class="ttd">
        <div class="ket-ttd">
            HRD. PT XYZ Semarang
        </div>
        <div class="img-ttd">
            <img src="../assets/images/ttd.png" />
        </div>
        <div class="nama-ttd">
            Muhammad Basri 
        </div>
    </div>
</body>

</html>

<?php 

$content = ob_get_clean();

try{ 
	$html2pdf= new HTML2PDF( 'P', 'A4', 'en'); 
	$html2pdf->WriteHTML($content); 
	$html2pdf->Output('lap_gaji.pdf');
}catch(HTML2PDF_Exception $ex){
	echo $ex;
}