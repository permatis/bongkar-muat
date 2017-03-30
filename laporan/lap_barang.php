<?php 
if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])) {header("Location: http://localhost/bongkaran/login.php"); exit;}

require_once( '../lib/Database.php'); 
require_once(dirname(__FILE__). '/html2pdf/html2pdf.class.php'); 

$data = Database::get('barang')->result(); 

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
            border: 1px solid #000;
            border-collapse: collapse;
        }
        td, th{
            height: 10px;
            padding: 5px 10px 5px 10px;
            border: 1px solid #000;
        }
        .tb_informasi {
            font-size: 14px;
            font-weight: bold;
        }
        .tgl-ttd, .ket-ttd,  .nama-ttd {
            text-align: right;
        }
        .tgl-ttd{
            margin-top: 50px;
            margin-right: 50px;
        }
        .ket-ttd{
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
    <h2 style="text-align:center">Laporan Data Barang</h2>
    <table>
        <tr>
            <th width="5">No</th>
            <th width="250">Nama Barang</th>
            <th width="160">Satuan</th>
            <th width="170">Harga</th>
        </tr>
        <?php 
        $no = 1;
        foreach($data as $d) : 
        ?>
	    <tr>
	    	<td><?php echo $no; ?></td>
	    	<td><?php echo ucfirst($d['nama']); ?></td>
	    	<td><?php echo $d['satuan']; ?></td>
	    	<td><?php echo "Rp. ".number_format($d['harga'], 0, '', '.'); ?></td>
	    </tr>

	    <?php $no++; ?>
    	<?php endforeach; ?>

    </table>
    <div class="ttd">
        <div class="tgl-ttd">
            <?php 
            	$tgl = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
            	$tanggal = $tgl->format('Y-m-d');

            	echo 'Semarang, '.Database::tglIndonesia($tanggal);
            ?>
        </div>
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
	$html2pdf->Output('lap_barang.pdf');
}catch(HTML2PDF_Exception $ex){
	echo $ex;
}