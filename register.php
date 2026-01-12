<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CRM System</title>
    <!-- Tailwind CSS + DaisyUI (Compiled) -->
    <link href="dist/output.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-base-200 min-h-screen flex items-center justify-center">
    <div class="card w-96 bg-base-100 shadow-xl my-10">
        <div class="card-body">
            <h2 class="card-title text-2xl font-bold mb-4">Daftar Akun</h2>
            <?php 
            require_once 'includes/functions.php';
            displayFlash(); 
            ?>
            <form action="process/auth_process.php" method="POST">
                <input type="hidden" name="action" value="register">
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Nama Lengkap</span>
                    </label>
                    <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" class="input input-bordered w-full" required />
                </div>
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Username</span>
                    </label>
                    <input type="text" name="username" placeholder="Username" class="input input-bordered w-full" required />
                </div>
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" name="email" placeholder="Email" class="input input-bordered w-full" required />
                </div>
                <div class="form-control w-full mt-2">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" name="password" placeholder="Password" class="input input-bordered w-full" required />
                </div>
                <div class="card-actions justify-end mt-6">
                    <button type="submit" class="btn btn-primary w-full">Daftar</button>
                </div>
            </form>
            <div class="text-center mt-4">
                <p class="text-sm">Sudah punya akun? <a href="index.php" class="link link-primary">Login di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
