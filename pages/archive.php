<?php
require_once '../includes/header.php';

// Fitur Pencarian di Sampah
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$where = "WHERE deleted_at IS NOT NULL";
if ($search) {
    $where .= " AND (nama_customer LIKE '%$search%' OR email LIKE '%$search%')";
}

$query = "SELECT * FROM data_customer $where ORDER BY deleted_at DESC";
$customers = mysqli_query($conn, $query);
?>

<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="flex items-center gap-4">
        <h2 class="text-2xl font-bold">Recycle Bin</h2>
        <div class="badge badge-error gap-2">
            <i class="fa-solid fa-trash-can text-[10px]"></i>
            <?php echo mysqli_num_rows($customers); ?> Data
        </div>
    </div>
    <a href="customer_list.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Customer</a>
</div>

<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <!-- Search Form -->
        <form method="GET" class="flex gap-2 mb-6 max-w-sm">
            <div class="form-control flex-1">
                <div class="input-group">
                    <input type="text" name="search" placeholder="Cari di sampah..." class="input input-bordered w-full" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-square">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
            <?php if ($search): ?>
                <a href="archive.php" class="btn btn-ghost btn-square" title="Reset">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            <?php endif; ?>
        </form>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th>Nama Customer</th>
                        <th>Email</th>
                        <th>Dihapus Pada</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($customers) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($customers)): ?>
                            <tr>
                                <td>
                                    <div class="font-bold"><?php echo $row['nama_customer']; ?></div>
                                    <div class="text-[10px] opacity-50 uppercase"><?php echo $row['no_telepon']; ?></div>
                                </td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <span class="text-xs opacity-70"><?php echo date('d M Y, H:i', strtotime($row['deleted_at'])); ?></span>
                                </td>
                                <td>
                                    <div class="flex justify-center gap-2">
                                        <a href="../process/customer_process.php?action=restore&id=<?php echo $row['id_customer']; ?>" class="btn btn-xs btn-success gap-1">
                                            <i class="fa-solid fa-undo"></i> Restore
                                        </a>
                                        <a href="../process/customer_process.php?action=permanent_delete&id=<?php echo $row['id_customer']; ?>" class="btn btn-xs btn-error gap-1" onclick="return confirm('Hapus permanen? Data tidak bisa dikembalikan!')">
                                            <i class="fa-solid fa-trash"></i> Hapus Permanen
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-10">
                                <i class="fa-solid fa-folder-open text-4xl opacity-20 mb-2 block"></i>
                                <p class="opacity-50 italic">Tidak ada data yang ditemukan di recycle bin.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/sidebar.php'; ?>