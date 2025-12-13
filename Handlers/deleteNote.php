<?php
require '../koneksi.php';

if(!$conn){
    die("koneksi gagal" . pg_last_error());
}

$id = $_POST['id']??null;
if($id){
    $sql= "DELETE FROM notes WHERE id =$1";
    $result = pg_query_params($conn,$sql,[$id]);

    if($result){
        echo("note berhasil didelete");
        echo ('<a href="../index.php"><button type="button">Kembali</button></a>');
    } else {
            echo ("error " . $sql .pg_last_error($conn));
        }
} else {
    echo("id tidak ditemukan");
}