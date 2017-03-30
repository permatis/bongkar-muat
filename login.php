<?php

if( !session_id() ) session_start();

if (isset($_SESSION['admin'])) header("Location: admin/index.php");


include_once 'lib/Kriptografi.php';
include_once 'lib/Database.php';

$nama       = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password   = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

//Check jika ada request post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nama'      => $nama,
        'password'  => $password
    ];

    $decrypted = Kriptografi::check($data);

    if($decrypted == TRUE){ 
        $_SESSION['admin'] = $nama;
        header("Location: admin/index.php");
    }else{ 
        $errors = '<div class="error-login">Username/password salah.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test login</title>
    <link rel="stylesheet" type="text/css" href="assets/images/style.css">
</head>
<body>
    <div class="wrap">
        <div class="header">
            <a href="index.php"><img src="assets/images/logo.png" alt="Logo"></a>
        </div>
    </div>
    <div class="login-box">
        <div class="login-form">
            <div class="login-info">Silahkan login dengan mengisi username dan password</div>
            <?php 
                if(isset($errors)) echo $errors;
            ?>
            <form method="post" action="" name="login" autocomplete="off" class="forms">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Ketik Username..." id="username" autofocus><br>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Ketik Password..." id="password"><br>
                </div>
                <div class="form-group">
                    <input type="submit" value="User Login">
                </div>
            </form>
        </div>
        <div class="copy">All Rights Reserved | Copyright &copy; - 2014 | Program Pengolahan Bongkar Muat</div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrapValidator.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.forms').bootstrapValidator({
                message: 'Tidak boleh kosong',
                fields: {
                    username: {
                        validators: {
                            notEmpty: {
                                message: 'Username harus diisi dan tidak boleh kosong.'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Password harus diisi dan tidak boleh kosong.'
                            }
                        }
                    }
                }
            });
        });
    </script>    
</body>
</html>