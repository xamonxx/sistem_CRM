<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "crm_database";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}