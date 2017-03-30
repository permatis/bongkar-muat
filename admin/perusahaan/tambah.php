<?php

if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])){ header("Location: ../../login.php");exit;}

require_once('../../lib/Database.php');
require_once('../../lib/Message.php');

//filter setiap inputan
$nama    = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
$alamat  = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
$telepon = filter_input(INPUT_POST, 'telepon', FILTER_SANITIZE_NUMBER_INT);

//Check jika ada request post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = [
        'nama'      => $nama,
        'alamat'    => $alamat,
        'telepon'   => $telepon
    ];

    Database::insert('perusahaan', $data);
    $msg = new Messages();

    return $msg->add('s', 'Data sudah berhasil ditambahkan.', 'index.php'); 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Data Perusahaan</title>
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
                    <h1>Tambah Data Perusahaan</h1>
                    <form method="post" action="" autocomplete="off" class="form">
                        Nama Perusahaan:<br>
                        <div class="form-group">
                            <input type="text" name="nama" placeholder="Isi nama perusahaan..." autofocus>
                        </div>
                        <br> 
                        Telepon:<br>
                        <div class="form-group">
                            <input type="text" name="telepon" placeholder="Isi telepon perusahaan...">
                        </div>
                        <br> 
                        Alamat:<br>
                        <div class="form-group">
                            <textarea name="alamat" placeholder="Isi alamat perusahaan..."></textarea>
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
    <script src="../../assets/js/bootstrapValidator.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.form').bootstrapValidator({
                message: 'Tidak boleh kosong',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    nama: {
                        validators: {
                            notEmpty: {
                                message: 'Nama perusahaan harus diisi dan tidak boleh kosong'
                            }
                        }
                    },
                    alamat: {
                        validators: {
                            notEmpty: {
                                message: 'Alamat harus diisi dan tidak boleh kosong'
                            }
                        }
                    },
                    telepon: {
                        validators:{
                            notEmpty:{
                                message: 'Telepon harus diisi dan tidak boleh kosong.'
                            },
                            numeric:{
                                message: 'Telepon harus berupa angka'
                            },
                            stringLength: {
                                min: 10,
                                max: 12,
                                message: 'Nomor HP harus lebih dari 10 dan kurang dari 12 karakter'
                            }
                        }
                    }
                            
                }
            });
        });
    </script> 
</body>
</html>