<?php
if( !session_id() ) session_start();

if (!isset($_SESSION['admin'])){ header("Location: ../../login.php");exit;}

require_once('../../lib/Database.php');
require_once('../../lib/Message.php');


if(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)){
    //filter setiap inputan
    $id                 = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $namaperusahaan     = filter_input(INPUT_POST, 'namaperusahaan', FILTER_SANITIZE_NUMBER_INT);
    $namabarang         = filter_input(INPUT_POST, 'namabarang', FILTER_SANITIZE_NUMBER_INT);
    $status             = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $jumlah             = filter_input(INPUT_POST, 'jumlah', FILTER_SANITIZE_NUMBER_INT);
    $anggota            = filter_input(INPUT_POST, 'anggota', FILTER_SANITIZE_STRING);

    //menampilkan keseluruhan data dari tabel bongkarmuat
    $data1      = Database::get('bongkarmuat')->where('id_bongkarmuat', '=', $id)->result();

    if(!($data1)) header("Location: index.php");

    //menampilkan nilai id dan nama perusahaan berdasarkan perusahaan_id pada tabel bongkarmuat
    $perusahaan = Database::query('select id_perusahaan, nama from perusahaan where id_perusahaan = '.$data1[0]['perusahaan_id'])->result();

    //menampilkan nilai id dan nama barang berdasarkan barang_id pada tabel bongkarmuat
    $barang     = Database::query('select id_barang, nama from barang where id_barang = '.$data1[0]['barang_id'])->result();
    
    //hanya menampilkan id anggota berdasarkan anggota_id pada tabel relasi_anggota
    $anggota_id = Database::query('select r.anggota_id from bongkarmuat b inner join relasi_anggota r on b.id_bongkarmuat = r.bongkarmuat_id where r.bongkarmuat_id = '.$id)->result();
    
    foreach ($anggota_id as $anggs) {
        $id_anggota[] = $anggs['anggota_id'];
    }

    //Check jika ada request post 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        //mendefinisikan tanggal
        $tgl = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $tanggal = $tgl->format('Y-m-d');

        //menentukan total
        $biaya = Database::query('select harga from barang where id_barang = '. $namabarang)->result();
        $harga = ($status == 'bongkar-muat') ? 2 : 1;
        $total = $jumlah * ($biaya[0]['harga'] * $harga);

        //mendefinisikan data input sesuai nama field didalam database.
        $data = [
            'status'        => $status,
            'jumlah_barang' => $jumlah,
            'total_harga'   => $total,
            'tanggal'       => $tanggal,
            'perusahaan_id' => $namaperusahaan,
            'barang_id'     => $namabarang
        ];

        //check jika input pegawai kosong maka kembali refresh halaman edit.php?id=$id
        if(!$anggota) header("Location: edit.php?id=$id");

        //insert data ke tabel bongkarmuat
        Database::update('bongkarmuat', $data)->where('id_bongkarmuat', '=', $id)->result();

        //pecah inputan pegawai dengan koma (,) 
        $id_pegawai = explode(",", $anggota);
        
        foreach ($id_pegawai as $peg) {
            $inputan[] = array('anggota_id' => $peg);
        }

        $get_id     = Database::get('relasi_anggota', ['id'])->where('bongkarmuat_id', '=', 1)->result();

        //insert data ke tabel relasi_anggota
        function updateRelasi($id_keys, $id_values, $input, $database)
        {
            if(count($input) == count($database)){
                for ($i=0; $i < count($input); $i++) { 
                    $input_data[] = array_merge($database[$i], $input[$i]);
                }

                $id_database = array_keys($database[0])[0];
                $inputs      = array_keys($input[0])[0];
                
                foreach ($input_data as $keys => $values) {
                    $q[] = "WHEN id = ".$values[$id_database]." THEN ". $values[$inputs];
                    $v[] = $values[$id_database];
                }
                
                $query = "UPDATE relasi_anggota SET anggota_id = CASE ".implode(" ", $q)." END WHERE id IN(".implode(",", $v).")";
                Database::query($query)->result();

            }else{

                $inputs_value = implode(", ", array_values($input[0]));
                $inputs_key =  array_keys($input[0])[0];

                foreach ($input as $key => $value) {
                    $values[] = "(".$id_values.",".$value[$inputs_key].")";
                }

                $delete = "DELETE FROM relasi_anggota WHERE ".$id_keys." = ".$id_values." AND ".$inputs_key." NOT IN (".$inputs_value.")";
                Database::query($delete)->result();
                
                $insert = "INSERT INTO relasi_anggota (".$id_keys.", ".$inputs_key.") VALUES ".implode(", ", $values)." ON DUPLICATE KEY UPDATE ".$id_keys."=".$id_keys;
                
                Database::query($insert)->result();
            }
        }

        //fungsi updateRelasi adalah memfilter data dari inputan kemudian difilter. Jika data sama maka tinggal diupdate, tapi jika berbeda maka dihapus kemudian ditambahkan.
        updateRelasi('bongkarmuat_id', $id, $inputan, $get_id);

        $msg = new Messages();

        return $msg->add('s', 'Data sudah berhasil ditambahkan.', 'index.php'); 
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Bongkar Muat</title>
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
                    <h1>Edit Data Bongkar Muat</h1>
                    <form method="post" action="edit.php?id=<?php echo $id;?>" autocomplete="off" class="form">
                        Nama Perusahaan:<br>
                        <div class="form-group">
                            <select name="namaperusahaan">
                                            <?php echo "<option value=".$perusahaan[0]['id_perusahaan'].">".$perusahaan[0]['nama'] ."</option>"; ?>
                                            <?php
                                                $p = Database::query("SELECT id_perusahaan, nama FROM perusahaan WHERE id_perusahaan NOT IN (".$perusahaan[0]['id_perusahaan'].")")->result();

                                                foreach($p as $v){
                                                    echo '<option value='.$v['id_perusahaan'].'>'.$v['nama'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div><br>
                                    <div class="form-group">
                                        <select name="namabarang">
                                            <?php echo "<option value=".$barang[0]['id_barang'].">".$barang[0]['nama'] ."</option>"; ?>
                                            <?php
                                                $b = Database::query('SELECT id_barang, nama FROM barang WHERE id_barang NOT IN ('.$barang[0]['id_barang'].')')->result();

                                                foreach($b as $k){
                                                    echo '<option value='.$k['id_barang'].'>'.$k['nama'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div><br>
                                    <div class="form-group">
                                        <select name="status">
                                            <?php 
                                                echo "<option value=".$data1[0]['status'].">".ucfirst($data1[0]['status'])."</option>";
                                            ?>
                                            <?php if($data1[0]['status'] != 'bongkar'): ?>
                                            <option value="bongkar">Bongkar</option>
                                            <?php endif; ?>
                                            <?php if($data1[0]['status'] != 'muat'): ?>
                                            <option value="muat">Muat</option>
                                            <?php endif; ?>
                                            <?php if($data1[0]['status'] != 'bongkar-muat'): ?>
                                            <option value="bongkar-muat">Bongkar-Muat</option>
                                            <?php endif; ?>
                                        </select>
                                    </div><br>
                                        <div class="form-group">
                                                <input type="text"  name="jumlah" placeholder="Isi jumlah barang bongkar muat..." value="<?php echo $data1[0]['jumlah_barang']?>">
                                        </div><br>
                                    <div class="form-group">
                                        <input type="text" name="anggota" id="anggota" placeholder="Tambahkan anggota..." value="<?php echo implode(',', $id_anggota); ?>">
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