<?php
if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])){ header("Location: ../../login.php"); exit;}

require_once('../../lib/Database.php');
require_once('../../lib/Message.php');

//filter setiap inputan
$nik            = filter_input(INPUT_POST, 'nik', FILTER_SANITIZE_NUMBER_INT);
$nama           = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
$no_hp          = filter_input(INPUT_POST, 'no_hp', FILTER_SANITIZE_NUMBER_INT);
$alamat         = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);

//Check jika ada request post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = [
        'nik'       => $nik,
        'nama'      => ucfirst($nama),
        'no_hp'     => $no_hp,
        'alamat'    => $alamat
    ];

    Database::insert('anggota', $data);
    $msg = new Messages();

    return $msg->add('s', 'Data sudah berhasil ditambahkan.', 'index.php'); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Anggota</title>
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
                    <h1>Tambah Data Anggota</h1>
                    <form method="post" action="" autocomplete="off" class="form">
                        NIK:<br>
                        <div class="form-group">
                            <input type="text" name="nik" placeholder="Isi NIK anggota..." autofocus>
                        </div>
                        <br> 
                        Nama Anggota:<br>
                        <div class="form-group">
                            <input type="text" name="nama" placeholder="Isi nama anggota..." autofocus>
                        </div>
                        <br> 
                        Nomor HP:<br>
                        <div class="form-group">
                            <input type="text" name="no_hp" placeholder="Isi nomor hp anggota...">
                        </div>
                        <br> 
                        Alamat:<br>
                        <div class="form-group">
                            <textarea name="alamat" placeholder="Isi alamat anggota..."></textarea>
                        </div>
                        <br>
                        <input type="submit" name="act" value="Simpan">
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
                fields: {
                    nik: {
                        validators: {
                            notEmpty: {
                                message: 'NIK harus diisi dan tidak boleh kosong '
                            },
                            stringLength: {
                                max: 12,
                                message: 'NIK tidak boleh lebih dari 12 karakter '
                            }
                        }
                    },
                    nama: {
                        validators: {
                            notEmpty: {
                                message: 'Nama anggota harus diisi dan tidak boleh kosong '
                            }
                        }
                    },
                    no_hp: {
                        validators: {
                            notEmpty: {
                                message: 'Nomor HP harus diisi dan tidak boleh kosong '
                            },
                            numeric:{
                                message: 'Nomor HP harus berupa angka '
                            },
                            stringLength: {
                                min: 10,
                                max: 12,
                                message: 'Nomor HP harus lebih dari 10 dan kurang dari 12 karakter '
                            }
                        }
                    },
                    alamat: {
                        validators: {
                            notEmpty: {
                                message: 'Alamat harus diisi dan tidak boleh kosong '
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>