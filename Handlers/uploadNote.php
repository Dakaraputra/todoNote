<?php 
require '../koneksi.php';

if(!$conn){
    die("koneksi gagal ". pg_last_error());
}
$judul = $_POST['title'];
$konten=$_POST['content'];
$fileName = $_FILES['notes_image']['name']??null;
$fileTmp = $_FILES['notes_image']['tmp_name']??null;

if($fileName && $fileTmp){
    $target_dir = '../uploads/';
    $targetFile = $target_dir.basename($fileName);
    if(move_uploaded_file($fileTmp,$targetFile)){
        $sql = "INSERT INTO notes (title,contents,image_path) VALUES ('$judul','$konten','$targetFile')";

        $result=pg_query($conn,$sql);

        if ($result){
            echo ("Data berhasil ditambahkan");
            echo ('<a href="../index.php"><button type="button">Kembali</button></a>');
        } else {
            echo ("error " . $sql .pg_last_error($conn));
        }
    }
} else {
    $sql = "INSERT INTO notes (title,contents) VALUES ('$judul','$konten')";

    $result=pg_query($conn,$sql);
    if ($result){
            echo ("Data berhasil ditambahkan");
            echo ('<a href="../index.php"><button type="button">Kembali</button></a>');
        } else {
            echo ("error " . $sql .pg_last_error($conn));
        }
}
?>