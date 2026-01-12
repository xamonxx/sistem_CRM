<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
    $filename = $_FILES['csv_file']['tmp_name'];
    
    if ($_FILES['csv_file']['size'] > 0) {
        $file = fopen($filename, "r");
        
        // Skip header
        fgetcsv($file);
        
        $success = 0;
        $failed = 0;
        $created_by = $_SESSION['user_id'];
        
        // Gunakan Prepared Statement untuk efisiensi dan keamanan
        $stmt = mysqli_prepare($conn, "INSERT INTO data_customer (nama_customer, email, no_telepon, sumber_customer, status_customer, alamat, catatan, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            // Mapping kolom sesuai file sample_import_customer.csv
            // 0: Nama, 1: Email, 2: Telepon, 3: Sumber, 4: Status, 5: Alamat, 6: Catatan
            if (count($column) < 5) continue; // Skip jika kolom krusial kurang

            $nama = $column[0];
            $email = $column[1];
            $telepon = $column[2];
            $sumber = $column[3];
            $status = $column[4];
            $alamat = isset($column[5]) ? $column[5] : '';
            $catatan = isset($column[6]) ? $column[6] : '';
            
            mysqli_stmt_bind_param($stmt, "sssssssi", $nama, $email, $telepon, $sumber, $status, $alamat, $catatan, $created_by);
            
            if (mysqli_stmt_execute($stmt)) {
                $success++;
            } else {
                $failed++;
            }
        }
        
        fclose($file);
        
        if ($success > 0) {
            logAktivitas($conn, $_SESSION['user_id'], "Import customer via CSV: Berhasil $success, Gagal $failed");
            setFlash('success', "Berhasil mengimpor $success data. (Gagal: $failed)");
        } else {
            setFlash('error', "Gagal mengimpor data! Pastikan format kolom sesuai.");
        }
    } else {
        setFlash('error', "File kosong!");
    }
} else {
    setFlash('error', "Silakan pilih file CSV.");
}

header("Location: ../pages/customer_list.php");
exit();
?>
