<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Pastikan hanya admin yang bisa mengakses proses ini
checkAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['action'])) {
    $action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];

    // 🟢 TAMBAH USER
    if ($action == 'add') {
        $nama = $_POST['nama_lengkap'];
        $user = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // 🛑 VALIDASI DUPLIKAT (Username atau Email)
        $stmt_check = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($stmt_check, "ss", $user, $email);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            setFlash('error', 'Gagal! Username atau Email sudah terdaftar di sistem.');
            header("Location: ../pages/user_management.php");
            exit();
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO users (nama_lengkap, username, email, role, password) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $user, $email, $role, $pass);
        
        if (mysqli_stmt_execute($stmt)) {
            logAktivitas($conn, $_SESSION['user_id'], "Admin menambah user baru: $user");
            setFlash('success', 'User berhasil ditambahkan!');
        } else {
            setFlash('error', 'Gagal menambah user!');
        }
        header("Location: ../pages/user_management.php");
        exit();
    }

    // 🔵 UPDATE USER
    if ($action == 'edit') {
        $id = $_POST['id_user'];
        $nama = $_POST['nama_lengkap'];
        $role = $_POST['role'];
        
        // Cek apakah password ingin diubah
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE users SET nama_lengkap = ?, role = ?, password = ? WHERE id_user = ?");
            mysqli_stmt_bind_param($stmt, "sssi", $nama, $role, $password, $id);
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE users SET nama_lengkap = ?, role = ? WHERE id_user = ?");
            mysqli_stmt_bind_param($stmt, "ssi", $nama, $role, $id);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            logAktivitas($conn, $_SESSION['user_id'], "Admin memperbarui data user ID: $id");
            setFlash('success', 'Data user berhasil diupdate!');
        } else {
            setFlash('error', 'Gagal update user!');
        }
        header("Location: ../pages/user_management.php");
        exit();
    }

    // 🟡 TOGGLE STATUS (Aktif/Nonaktif)
    if ($action == 'toggle_status') {
        $id = $_GET['id'];
        
        // Ambil status saat ini menggunakan Prepared Statement
        $stmt_get = mysqli_prepare($conn, "SELECT status_akun, username FROM users WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt_get, "i", $id);
        mysqli_stmt_execute($stmt_get);
        $result = mysqli_stmt_get_result($stmt_get);
        $user_data = mysqli_fetch_assoc($result);
        
        if ($user_data) {
            $new_status = ($user_data['status_akun'] == 'aktif') ? 'nonaktif' : 'aktif';
            
            $stmt_up = mysqli_prepare($conn, "UPDATE users SET status_akun = ? WHERE id_user = ?");
            mysqli_stmt_bind_param($stmt_up, "si", $new_status, $id);
            
            if (mysqli_stmt_execute($stmt_up)) {
                logAktivitas($conn, $_SESSION['user_id'], "Mengubah status user " . $user_data['username'] . " menjadi $new_status");
                setFlash('success', 'Status user berhasil diperbarui!');
            }
        }
        header("Location: ../pages/user_management.php");
        exit();
    }
}
?>