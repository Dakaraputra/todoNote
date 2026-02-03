<?php
$connStr = getenv("DATABASE_URL");
$db = pg_connect($connStr);

if (!$db) {
    die("Koneksi gagal: " . pg_last_error());
}
?>