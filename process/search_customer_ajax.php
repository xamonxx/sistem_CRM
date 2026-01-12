<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$sumber = isset($_GET['sumber']) ? mysqli_real_escape_string($conn, $_GET['sumber']) : '';

$where = "WHERE deleted_at IS NULL";
if ($search) $where .= " AND (nama_customer LIKE '%$search%' OR email LIKE '%$search%')";
if ($status) $where .= " AND status_customer = '$status'";
if ($sumber) $where .= " AND sumber_customer = '$sumber'";

// Kita tidak pakai pagination untuk real-time search supaya semua yang cocok muncul, 
// atau bisa batasi LIMIT 20 untuk performa.
$query = "SELECT * FROM data_customer $where ORDER BY created_at DESC LIMIT 50";
$customers = mysqli_query($conn, $query);

if (mysqli_num_rows($customers) > 0) {
    while ($row = mysqli_fetch_assoc($customers)) {
        $badge = 'badge-ghost';
        if ($row['status_customer'] == 'Aktif') $badge = 'badge-success';
        if ($row['status_customer'] == 'Prospek') $badge = 'badge-info';
        if ($row['status_customer'] == 'Tidak Aktif') $badge = 'badge-error';

        $json_row = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
        $tanggal = date('d M Y', strtotime($row['tanggal_daftar']));

        echo "
        <tr>
            <td>
                <input type='checkbox' name='customer_ids[]' value='{$row['id_customer']}' class='checkbox checkbox-sm customer-checkbox'>
            </td>
            <td>
                <div class='font-bold'>{$row['nama_customer']}</div>
                <div class='text-[10px] opacity-50 uppercase'>{$tanggal}</div>
            </td>
            <td>
                <div class='text-xs'>{$row['email']}</div>
                <div class='text-xs'>{$row['no_telepon']}</div>
            </td>
            <td>
                <div class='badge badge-ghost text-[10px] uppercase'>{$row['sumber_customer']}</div>
            </td>
            <td>
                <div class='badge {$badge} font-semibold text-[10px] uppercase'>{$row['status_customer']}</div>
            </td>
            <td>
                <div class='flex gap-1'>
                    <button type='button' class='btn btn-xs btn-info' onclick='editCustomer({$json_row})'>
                        <i class='fa-solid fa-edit'></i>
                    </button>
                    <a href='../process/customer_process.php?action=delete&id={$row['id_customer']}' class='btn btn-xs btn-error' onclick=\"return confirm('Hapus customer ini (Soft Delete)?')\">
                        <i class='fa-solid fa-trash'></i>
                    </a>
                    <a href='customer_detail.php?id={$row['id_customer']}' class='btn btn-xs btn-ghost'>
                        <i class='fa-solid fa-eye'></i>
                    </a>
                </div>
            </td>
        </tr>
        ";
    }
} else {
    echo "<tr><td colspan='6' class='text-center opacity-50 italic py-10'>Data tidak ditemukan.</td></tr>";
}
