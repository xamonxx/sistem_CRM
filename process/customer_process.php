<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['action'])) {
    $action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];

    // 🟢 CREATE (TAMBAH)
    if ($action == 'add') {
        $nama = $_POST['nama_customer'];
        $email = $_POST['email'];
        $telepon = $_POST['no_telepon'];
        $alamat = $_POST['alamat'];
        $sumber = $_POST['sumber_customer'];
        $status = $_POST['status_customer'];
        $catatan = $_POST['catatan'];
        $created_by = $_SESSION['user_id'];

        $stmt = mysqli_prepare($conn, "INSERT INTO data_customer (nama_customer, email, no_telepon, alamat, sumber_customer, status_customer, catatan, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssssi", $nama, $email, $telepon, $alamat, $sumber, $status, $catatan, $created_by);
        
        if (mysqli_stmt_execute($stmt)) {
            logAktivitas($conn, $_SESSION['user_id'], "Menambah customer: $nama");
            setFlash('success', 'Customer berhasil ditambahkan!');
        } else {
            setFlash('error', 'Gagal menambah customer!');
        }
        header("Location: ../pages/customer_list.php");
        exit();
    }

    // 🔵 UPDATE (EDIT)
    if ($action == 'edit') {
        $id = $_POST['id_customer'];
        $nama = $_POST['nama_customer'];
        $email = $_POST['email'];
        $telepon = $_POST['no_telepon'];
        $alamat = $_POST['alamat'];
        $sumber = $_POST['sumber_customer'];
        $status = $_POST['status_customer'];
        $catatan = $_POST['catatan'];

        $stmt = mysqli_prepare($conn, "UPDATE data_customer SET nama_customer=?, email=?, no_telepon=?, alamat=?, sumber_customer=?, status_customer=?, catatan=? WHERE id_customer=?");
        mysqli_stmt_bind_param($stmt, "sssssssi", $nama, $email, $telepon, $alamat, $sumber, $status, $catatan, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            logAktivitas($conn, $_SESSION['user_id'], "Mengedit customer: $nama");
            setFlash('success', 'Customer berhasil diupdate!');
        } else {
            setFlash('error', 'Gagal update customer!');
        }
        header("Location: ../pages/customer_list.php");
        exit();
    }

    // 🔴 DELETE (SOFT DELETE)
    if ($action == 'delete') {
        $id = $_GET['id'];
        $now = date('Y-m-d H:i:s');
        $stmt = mysqli_prepare($conn, "UPDATE data_customer SET deleted_at = ? WHERE id_customer = ?");
        mysqli_stmt_bind_param($stmt, "si", $now, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            logAktivitas($conn, $_SESSION['user_id'], "Menghapus customer (soft delete) ID: $id");
            setFlash('success', 'Customer dipindahkan ke Recycle Bin!');
        }
        header("Location: ../pages/customer_list.php");
        exit();
    }

    // 🟢 RESTORE
    if ($action == 'restore') {
        $id = $_GET['id'];
        $stmt = mysqli_prepare($conn, "UPDATE data_customer SET deleted_at = NULL WHERE id_customer = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            logAktivitas($conn, $_SESSION['user_id'], "Restore customer ID: $id");
            setFlash('success', 'Customer berhasil dikembalikan!');
        }
        header("Location: ../pages/archive.php");
        exit();
    }

    // 🔴 PERMANENT DELETE
    if ($action == 'permanent_delete') {
        $id = $_GET['id'];
        $stmt = mysqli_prepare($conn, "DELETE FROM data_customer WHERE id_customer = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            logAktivitas($conn, $_SESSION['user_id'], "Hapus permanen customer ID: $id");
            setFlash('success', 'Customer berhasil dihapus permanen!');
        }
        header("Location: ../pages/archive.php");
        exit();
    }
    // 🔴 BULK DELETE (SOFT DELETE MULTIPLE)
    if ($action == 'bulk_delete') {
        if (isset($_POST['customer_ids']) && is_array($_POST['customer_ids'])) {
            $ids = $_POST['customer_ids'];
            $now = date('Y-m-d H:i:s');
            $count = 0;

            foreach ($ids as $id) {
                $stmt = mysqli_prepare($conn, "UPDATE data_customer SET deleted_at = ? WHERE id_customer = ?");
                mysqli_stmt_bind_param($stmt, "si", $now, $id);
                if (mysqli_stmt_execute($stmt)) {
                    $count++;
                }
            }

            if ($count > 0) {
                logAktivitas($conn, $_SESSION['user_id'], "Bulk delete customer: $count data");
                setFlash('success', "$count customer berhasil dipindahkan ke Recycle Bin!");
            } else {
                setFlash('error', "Gagal menghapus data.");
            }
        } else {
            setFlash('error', "Tidak ada customer yang dipilih.");
        }
        header("Location: ../pages/customer_list.php");
        exit();
    }
}
?>