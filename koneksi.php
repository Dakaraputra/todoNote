<?php
$host     = 'localhost';
$port     = '5433'; 
$user     = 'postgres';
$password = 'dakput01';
$db       = 'todonote'; 

$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$password");

if (!$conn) {
    die("Koneksi Gagal: " . pg_last_error());
}
?>