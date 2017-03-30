<?php
if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])){ header("Location: ../../login.php"); exit;}

require_once('../../lib/Database.php');
require_once('../../lib/Message.php');

$data = Database::get('barang')->result();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Barang</title>
    <link rel="stylesheet" type="text/css" href="../../assets/images/style.css">
</head>

<body>
    <div class="wrap">
        <div class="header">
            <a href="../../index.php"><img src="../../assets/images/logo.png" alt="Logo"></a>
        </div>

        <div class="page">
            <?php include_once '../../navigasi.php';?>
            <div class="p-right">
                <div class="box">
                    <h1>Data Barang</h1>
                    <p>
                        <a href="tambah.php" title="Tambah Barang"><img src="../../assets/images/s_okay.png">
                            <label class="tambah">tambah</label>
                        </a> &nbsp; &nbsp;
                        <a href="../../laporan/lap_barang.php" title="Cetak Anggota">
                            <img src="../../assets/images/printer.png">
                            <label class="tambah">cetak</label>
                        </a>
                    </p>
                    <table border="0">
                        <tr class="head">
                            <td width="250">Nama Barang</td>
                            <td width="120">Satuan</td>
                            <td width="170">Harga</td>
                            <td width="100" align="center">Aksi</td>
                        </tr>
                        <?php if($data): ?>
                        <?php foreach($data as $d) : ?>
                        <tr class="data">
                            <td>
                                <?php echo ucfirst($d['nama']); ?>
                            </td>
                            <td>
                                <?php echo $d['satuan']; ?>
                            </td>
                            <td>
                                <?php echo "Rp. ".number_format($d['harga'], 0, '', '.'); ?>
                            </td>
                            <td align="center">
                                <a href="edit.php?id=<?php echo $d['id_barang']; ?>" title="Ubah Barang"><img src="../../assets/images/b_edit.png">
                                </a> &nbsp
                                <a href="hapus.php?id=<?php echo $d['id_barang']; ?>" title="Hapus Barang"><img src="../../assets/images/b_drop.png">
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else : ?>
                        <tr class="no-data">
                            <td colspan="6">Maaf, belum ada data barang saat ini</td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    <p>Gunakan tombol Edit dan Delete untuk manipulasi data lebih lanjut.</p>
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