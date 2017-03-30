<?php 
if( !session_id() ) session_start();

include_once 'lib/Database.php';

$query = "
    SELECT
    a.id_anggota,
    a.nama,
    a.alamat,
    a.no_hp,
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
) a ON a.bongkarmuat_id = b.id_bongkarmuat
INNER JOIN anggota a ON r.anggota_id = a.id_anggota
GROUP BY
    a.nama
";

$data = Database::query($query)->result(); 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Daftar Gaji Anggota</title>
    <link rel="stylesheet" type="text/css" href="assets/images/style.css">
</head>

<body>
    <div class="wrap">
        <div class="header">
            <a href="index.php"><img src="assets/images/logo.png" alt="Logo"></a>
        </div>

        <div class="page">
            <div class="box">
                <h1>
                    Daftar Gaji Anggota
                    <small>
                        <?php if(isset($_SESSION['admin'])): ?>
                            <a href="admin/index.php"> kembali ke admin </a>
                        <?php else : ?>
                            <a href="login.php"> login</a>
                        <?php endif; ?>
                    </small>
                </h1>
                <p>Berikut ini adalah gaji anggota pada hari ini:</p>
                <table border="0">
                    <tr class="head">
                        <td width="200">Nama Anggota</td>
                        <td width="350">Alamat</td>
                        <td width="180">No HP</td>
                        <td width="220">Gaji Hari ini</td>
                    </tr>
                    <?php if($data): ?>
                    <?php foreach($data as $d) : ?>
                    <tr class="data">
                        <td>
                            <a href="detail.php?id=<?php echo $d['id_anggota']; ?>"><?php echo $d['nama']; ?>
                        </td>
                        <td>
                            <?php echo $d['alamat']; ?>
                        </td>
                        <td>
                            <?php echo $d['no_hp']; ?>
                        </td>
                        <td>
                            <?php echo "Rp. ".number_format($d['pendapatan'], 0, '', '.'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <tr class="no-data">
                        <td colspan="6">Maaf, hari ini sedang libur.</td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <div class="footer">
            All Rights Reserved | Copyright &copy; - 2014 | Program Pengolahan Bongkar Muat
        </div>
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