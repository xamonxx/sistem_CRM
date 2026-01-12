<?php
require_once '../includes/header.php';

$query = "SELECT f.*, c.nama_customer FROM follow_up f JOIN data_customer c ON f.id_customer = c.id_customer ORDER BY f.tanggal_followup DESC";
$followups = mysqli_query($conn, $query);
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold">Semua Jadwal Follow Up</h2>
</div>

<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Catatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($followups) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($followups)): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($row['tanggal_followup'])); ?></td>
                            <td class="font-bold"><?php echo $row['nama_customer']; ?></td>
                            <td class="max-w-xs truncate"><?php echo $row['catatan']; ?></td>
                            <td>
                                <?php 
                                $badge = 'badge-ghost';
                                if($row['status_followup'] == 'Selesai') $badge = 'badge-success';
                                if($row['status_followup'] == 'Terjadwal') $badge = 'badge-info';
                                ?>
                                <div class="badge <?php echo $badge; ?>"><?php echo $row['status_followup']; ?></div>
                            </td>
                            <td>
                                <a href="customer_detail.php?id=<?php echo $row['id_customer']; ?>" class="btn btn-xs btn-ghost">Detail</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">Belum ada data follow up.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/sidebar.php'; ?>
