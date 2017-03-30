<?php

if( !session_id() ) session_start();

require_once('../../lib/Database.php');
require_once('../../lib/Message.php');

if(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)){
    
    $msg = new Messages();
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    Database::delete('barang')->where('id_barang', '=',$id)->result();

    return $msg->add('s', 'Data berhasil dihapus.', 'index.php'); 
}else{
    header("Location: index.php");
}

?>