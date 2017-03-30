<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Konfigurasi Pengguna</title>
    <link href="assets/css/datepicker.css" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Sistem Pengelolaan Bongkar Muat</a>
            </div>
            <ul class="nav navbar-right top-nav">        
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href=""><i class="fa fa-fw fa-gear"></i> Settings</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li><a href="anggota/"><i class="fa fa-fw fa-group"></i> Data Anggota</a></li>
                    <li><a href="barang/"><i class="fa fa-fw fa-briefcase"></i> Data Barang</a></li>
                    <li><a href="bongkar-muat/"><i class="fa fa-fw fa-car"></i> Data Bongkar Muat</a></li>
                    <li><a href="perusahaan/"><i class="fa fa-fw fa-building-o"></i> Data Perusahaan</a></li>
                </ul>
            </div>
        </nav>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                        <div class="panel panel-default form-panel">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Konfigurasi Pengguna</h3>
                            </div>
                            <div class="panel-body">
                                <form action="" class="form-container forms">
                                    <div class="form-group">
                                        <input type="text" class="form-control input-lg" name="nama" placeholder="Nama">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-lg" name="passwordlama" placeholder="Password Lama">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-lg" name="passwordbaru" placeholder="Password Baru">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-lg" name="passwordulang" placeholder="Ulangi Password">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-success btn-lg" value="Perbarui">
                                        <input type="reset" class="btn btn-default btn-lg" value="Cancel">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/datepicker.js"></script>
</body>
</html>