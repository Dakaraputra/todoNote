<?php 
require '../koneksi.php';

if(!$conn){
    die("koneksi gagal ". pg_last_error());
}
$tugas = $_POST['task'];
$fileName = $_FILES['todo_image']['name']??null;
$fileTmp = $_FILES['todo_image']['tmp_name']??null;

if($fileName && $fileTmp){
    $target_dir = '../uploads/';
    $targetFile = $target_dir.basename($fileName);

    if(move_uploaded_file($fileTmp,$targetFile)){
        $sql = "INSERT INTO todos (task,image_path) VALUES ('$tugas','$targetFile')";

        $result=pg_query($conn,$sql);

        if ($result){
            echo ("Data berhasil ditambahkan");
            echo ('<a href="../index.php"><button type="button">Kembali</button></a>');
        } else {
            echo ("error " . $sql .pg_last_error($conn));
        }
    }
} else {
    $sql = "INSERT INTO todos (task) VALUES ('$tugas')";

    $result=pg_query($conn,$sql);
    if ($result){
            echo ("Data berhasil ditambahkan");
            echo ('<a href="../index.php"><button type="button">Kembali</button></a>');
        } else {
            echo ("error " . $sql .pg_last_error($conn));
        }
}
?>