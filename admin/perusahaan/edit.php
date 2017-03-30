<?php

if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])){ header("Location: ../../login.php");exit;}

require_once('../../lib/Database.php');
require_once('../../lib/Message.php');

if(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)){

    //filter setiap inputan
    $id      = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nama    = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
    $alamat  = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
    $telepon = filter_input(INPUT_POST, 'telepon', FILTER_SANITIZE_NUMBER_INT);

    $data1 = Database::get('perusahaan')->where('id_perusahaan','=', $id)->result();
    foreach ($data1 as $d) {
        $perusahaan = $d;
    }

    if(empty($data1)) header("Location: index.php");

    //Check jika ada request post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $data = [
            'nama'      => $nama,
            'alamat'    => $alamat,
            'telepon'   => $telepon
        ];

        Database::update('perusahaan', $data)->where('id_perusahaan', '=', $id)->result();
        $msg = new Messages();

        return $msg->add('s', 'Data sudah berhasil ditambahkan.', 'index.php'); 
    }
}else{
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Data Perusahaan</title>
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
                    <h1>Update Data Perusahaan</h1>
                    <form method="post" action="edit.php?id=<?php echo $id; ?>" autocomplete="off" class="form">
                        Nama Perusahaan:<br>
                        <div class="form-group">
                            <input type="text" name="nama" placeholder="Isi nama perusahaan..." value="<?php echo $data1[0]['nama'];?>" autofocus>
                        </div>
                        <br> 
                        Telepon:<br>
                        <div class="form-group">
                            <input type="text" name="telepon" placeholder="Isi telepon perusahaan..." value="<?php echo $data1[0]['telepon'];?>">
                        </div>
                        <br> 
                        Alamat:<br>
                        <div class="form-group">
                            <textarea name="alamat" placeholder="Isi alamat perusahaan..."><?php echo $data1[0]['alamat'];?></textarea>
                        </div>
                        <br>
                        <input type="submit" value="Update">
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