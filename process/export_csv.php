<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_customer_'.date('Y-m-d').'.csv');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'Nama Customer', 'Email', 'Telepon', 'Sumber', 'Status', 'Tanggal Daftar'));

$query = "SELECT id_customer, nama_customer, email, no_telepon, sumber_customer, status_customer, tanggal_daftar FROM data_customer WHERE deleted_at IS NULL ORDER BY created_at DESC";
$rows = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($rows)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
