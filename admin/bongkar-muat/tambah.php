<?php
if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])){ header("Location: ../../login.php");exit;}

require_once('../../lib/Database.php');
require_once('../../lib/Message.php');

//filter setiap inputan
$namaperusahaan     = filter_input(INPUT_POST, 'namaperusahaan', FILTER_SANITIZE_NUMBER_INT);
$namabarang         = filter_input(INPUT_POST, 'namabarang', FILTER_SANITIZE_NUMBER_INT);
$status             = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
$jumlah             = filter_input(INPUT_POST, 'jumlah', FILTER_SANITIZE_NUMBER_INT);
$anggota            = filter_input(INPUT_POST, 'anggota', FILTER_SANITIZE_STRING);

//Check jika ada request post 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //menentukan nilai id pada tabel bongkarmuat dan tabel relasi_anggota
    $id_bongkar_muat = Database::get('bongkarmuat', ['id_bongkarmuat'])->orderby('id_bongkarmuat', 'DESC')->limit('1')->result();
    $id = (!$id_bongkar_muat) ? 1 : $id_bongkar_muat[0]['id_bongkarmuat']+1;

    //menentukan total
    $biaya = Database::get('barang',['harga'])->where('id_barang', '=', $namabarang)->result();
    //jika statusnya bongkar-muat maka harga barang dikalika 2
    $harga = ($status == 'bongkar-muat') ? 2 : 1;
    $total = $jumlah * ($biaya[0]['harga'] * $harga);

    //mendefinisikan tanggal
    $tgl = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
    $tanggal = $tgl->format('Y-m-d');

    //mendefinisikan data input sesuai nama field didalam database.
    $data = [
        'id_bongkarmuat'=> $id,
        'status'        => $status,
        'jumlah_barang' => $jumlah,
        'total_harga'   => $total,
        'tanggal'       => $tanggal,
        'perusahaan_id' => $namaperusahaan,
        'barang_id'     => $namabarang
    ];var_dump($data);

    //check jika input pegawai kosong maka kembali refresh halaman tambah.php
    if(!$anggota) header("Location: tambah.php");

    //insert data ke tabel bongkarmuat
    Database::insert('bongkarmuat', $data);

    //pecah inputan pegawai dengan koma (,) 
    $id_pegawai = explode(",", $anggota);
    
    foreach ($id_pegawai as $peg) {
        $relasi[] = [
            'bongkarmuat_id' => $id,
            'anggota_id' => $peg
        ];
    }

    //insert data ke tabel relasi_anggota
    Database::insert('relasi_anggota', $relasi);
    $msg = new Messages();

    return $msg->add('s', 'Data sudah berhasil ditambahkan.', 'index.php'); 
}

?>
    
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Data Bongkar Muat</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/selectize.css">
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
                    <h1>Tambah Data Bongkar Muat</h1>
                    <form method="post" action="" autocomplete="off" class="form">
                        Nama Perusahaan:<br>
                        <div class="form-group">
                            <select name="namaperusahaan">
                                <option value="">- Pilih Perusahaan -</option>
                                <?php
                                    $p = Database::get('perusahaan',['id_perusahaan','nama'])->result();

                                    foreach($p as $v){
                                        echo '<option value='.$v['id_perusahaan'].'>'.$v['nama'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <br> 
                        Nama Barang:<br>
                        <div class="form-group">
                            <select name="namabarang">
                                <option value="">- Pilih Barang -</option>
                                <?php
                                    $b = Database::get('barang',['id_barang','nama'])->result();

                                    foreach($b as $k => $v){
                                        echo '<option value='.$v['id_barang'].'>'.$v['nama'].'</option>';
                                    }
                                ?>
                            </select>

                        </div>
                        <br>
                        Status:<br>
                        <div class="form-group">
                            <select name="status">
                                <option value="bongkar">Bongkar</option>
                                <option value="muat">Muat</option>
                                <option value="bongkar-muat">Bongkar-Muat</option>
                            </select>
                        </div>
                        <br>
                        Jumlah:<br>
                        <div class="form-group">
                            <input type="text" name="jumlah" placeholder="Isi jumlah barang bongkar muat..." autofocus>
                        </div>
                        <br>
                        Anggota:<br>
                        <div class="form-group">
                            <input type="text" name="anggota" id="anggota" placeholder="Tambahkan anggota..." autofocus>
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
    <script src="../../assets/js/selectize.min.js"></script>
    <script src="../../assets/js/bootstrapValidator.min.js"></script>
    <script type="text/javascript">
        $('#anggota').selectize({
            plugins: ['remove_button'],
            persist: false,
            preload: true,
            openOnFocus: true,
            valueField: 'id_anggota',
            labelField: 'nama',
            maxItems: 5,
            options:
                <?php 
                    $a = Database::get('anggota', ['id_anggota', 'nama'])->result();
                    foreach ($a as $f) {
                        $angg[] = $f;
                    }

                    echo json_encode($angg);
                ?>,
            create: false,
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.form').bootstrapValidator({
                message: 'Tidak boleh kosong',
                fields: {
                    namaperusahaan: {
                        validators: {
                            notEmpty: {
                                message: 'Harus memilih perusahaannya'
                            }
                        }
                    },
                    namabarang: {
                        validators: {
                            notEmpty: {
                                message: 'Harus memilih barangnya'
                            }
                        }
                    },
                    jumlah: {
                        validators: {
                            notEmpty: {
                                message: 'Jumlah barang harus diisi dan tidak boleh kosong'
                            },
                            numeric:{
                                message: 'Jumlah barang harus berupa angka'
                            }
                        }
                    },
                    anggota: {
                        validators: {
                            notEmpty: {
                                message: 'Harus memilih pegawainya'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>