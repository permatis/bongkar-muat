<?php
if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])){ header("Location: ../../login.php"); exit;}

require_once('../../lib/Database.php');
require_once('../../lib/Message.php');

//filter setiap inputan
$nama     = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
$satuan   = filter_input(INPUT_POST, 'satuan', FILTER_SANITIZE_STRING);
$harga    = filter_input(INPUT_POST, 'harga', FILTER_SANITIZE_STRING);

//Check jika ada request post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = [
        'nama'      => $nama,
        'satuan'    => $satuan,
        'harga'     => str_replace('.', '', $harga)
    ];

    Database::insert('barang', $data);
    $msg = new Messages();

    return $msg->add('s', 'Data sudah berhasil ditambahkan.', 'index.php'); 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Data Barang</title>
    <link rel="stylesheet" type="text/css" href="../../assets/images/style.css">
</head>

<body>
    <div class="wrap">
        <div class="header">
            <a href="../../index.php"><img src="../../assets/images/logo.png" alt="Logo"></a>
        </div>

        <div class="page">
            <?php include_once '../../navigasi.php'; ?>
            <div class="p-right">
                <div class="box">
                    <h1>Tambah Data Barang</h1>
                    <form method="post" action="" autocomplete="off" class="form">
                        Nama Barang:<br>
                        <div class="form-group">
                            <input type="text" name="nama" placeholder="Isi nama barang..." autofocus>
                        </div>
                        <br>
                        Satuan:<br>
                        <div class="form-group">
                            <input type="text" name="satuan" placeholder="Isi satuan barang...">
                        </div>
                        <br>
                        Harga:<br>
                        <div class="form-group">
                            <label class="rupiah">Rp. </label><input type="text" name="harga" id="harga" onkeydown="return numbersonly(this, event);" placeholder="Isi harga barang...">
                        </div>
                        <br>
                        <input type="submit" value="Simpan">
                    </form>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="footer">
            All Rights Reserved | Copyright &copy; - 2014 | Program Pengolahan Bongkar Muat
        </div>
    </div>

    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/formatrupiah.js"></script>
    <script src="../../assets/js/bootstrapValidator.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.form').bootstrapValidator({
                message: 'Tidak boleh kosong',
                fields: {
                    nama: {
                        validators: {
                            notEmpty: {
                                message: 'Nama harus diisi dan tidak boleh kosong.'
                            }
                        }
                    },
                    satuan: {
                        validators: {
                            notEmpty: {
                                message: 'Satuan harus diisi dan tidak boleh kosong.'
                            }
                        }
                    },
                    harga: {
                        validators: {
                            notEmpty: {
                                message: 'Harga harus diisi dan tidak boleh kosong.'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>