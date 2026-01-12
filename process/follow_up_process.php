<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['action'])) {
    $action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];

    if ($action == 'add') {
        $id_customer = mysqli_real_escape_string($conn, $_POST['id_customer']);
        $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal_followup']);
        $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);
        $status = mysqli_real_escape_string($conn, $_POST['status_followup']);

        $query = "INSERT INTO follow_up (id_customer, tanggal_followup, catatan, status_followup) 
                  VALUES ('$id_customer', '$tanggal', '$catatan', '$status')";
        
        if (mysqli_query($conn, $query)) {
            logAktivitas($conn, $_SESSION['user_id'], "Menambah follow up untuk customer ID: $id_customer");
            setFlash('success', 'Follow up berhasil dicatat!');
        } else {
            setFlash('error', 'Gagal mencatat follow up!');
        }
        header("Location: ../pages/customer_detail.php?id=$id_customer");
        exit();
    }

    if ($action == 'delete') {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $cid = mysqli_real_escape_string($conn, $_GET['cid']);
        
        $query = "DELETE FROM follow_up WHERE id_followup = '$id'";
        if (mysqli_query($conn, $query)) {
            setFlash('success', 'Follow up dihapus!');
        } else {
            setFlash('error', 'Gagal menghapus!');
        }
        header("Location: ../pages/customer_detail.php?id=$cid");
        exit();
    }
}
?>
