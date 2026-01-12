<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND status_akun = 'aktif'");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time();

                logAktivitas($conn, $user['id_user'], "User login");
                header("Location: ../pages/dashboard.php");
                exit();
            } else {
                setFlash('error', 'Password salah!');
            }
        } else {
            setFlash('error', 'Username tidak ditemukan!');
        }
        header("Location: ../index.php");
        exit();
    }

    if ($action == 'register') {
        $nama = $_POST['nama_lengkap'];
        $user = $_POST['username'];
        $email = $_POST['email'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check availability
        $stmt_check = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($stmt_check, "ss", $user, $email);
        mysqli_stmt_execute($stmt_check);
        if (mysqli_stmt_get_result($stmt_check)->num_rows > 0) {
            setFlash('error', 'Username atau Email sudah terdaftar!');
            header("Location: ../register.php");
            exit();
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO users (nama_lengkap, username, email, password) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $user, $email, $pass);
        
        if (mysqli_stmt_execute($stmt)) {
            setFlash('success', 'Registrasi berhasil! Silakan login.');
            header("Location: ../index.php");
        } else {
            setFlash('error', 'Registrasi gagal!');
            header("Location: ../register.php");
        }
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
