<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

// Tentukan nama file
$filename = "Data_Customer_" . date('Ymd_His') . ".xls";

// Header untuk mendownload file Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// Ambil data dari database
$query = "SELECT nama_customer, email, no_telepon, alamat, sumber_customer, status_customer, tanggal_daftar 
          FROM data_customer 
          WHERE deleted_at IS NULL 
          ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<style>
    .table-export {
        border-collapse: collapse;
        width: 100%;
        font-family: Arial, sans-serif;
    }

    .table-export th {
        background-color: #570df8;
        /* Primary Color CRM */
        color: #ffffff;
        font-weight: bold;
        text-align: center;
        border: 1px solid #000000;
        padding: 10px;
    }

    .table-export td {
        border: 1px solid #000000;
        padding: 8px;
        vertical-align: middle;
        color: #000000;
        /* Warna Hitam */
    }

    .zebra {
        background-color: #f2f2f2;
    }

    .status-aktif {
        color: #008000;
        font-weight: bold;
    }

    .status-prospek {
        color: #0000ff;
        font-weight: bold;
    }
</style>

<table class="table-export">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Customer</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Alamat</th>
            <th>Sumber</th>
            <th>Status</th>
            <th>Tanggal Daftar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)):
            $zebra = ($no % 2 == 0) ? 'class="zebra"' : '';
        ?>
            <tr <?php echo $zebra; ?>>
                <td style="text-align: center;"><?php echo $no++; ?></td>
                <td><?php echo $row['nama_customer']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>'<?php echo $row['no_telepon']; ?></td> <!-- Tanda petik agar tidak diubah excel jadi angka scientist -->
                <td><?php echo $row['alamat']; ?></td>
                <td><?php echo $row['sumber_customer']; ?></td>
                <td style="text-align: center;">
                    <?php echo $row['status_customer']; ?>
                </td>
                <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($row['tanggal_daftar'])); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
exit();
?>