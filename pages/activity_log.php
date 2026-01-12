<?php
require_once '../includes/header.php';
// Pastikan hanya admin yang bisa melihat
checkAdmin();

// Filter Tanggal
$filter_day = isset($_GET['day']) && $_GET['day'] !== '' ? (int)$_GET['day'] : 0;
$filter_month = isset($_GET['month']) && $_GET['month'] !== '' ? (int)$_GET['month'] : 0;
$filter_year = isset($_GET['year']) && $_GET['year'] !== '' ? (int)$_GET['year'] : 0;

// Build WHERE clause
$where = "1=1";
if ($filter_day > 0 && $filter_month > 0 && $filter_year > 0) {
    $where .= " AND DAY(l.created_at) = $filter_day AND MONTH(l.created_at) = $filter_month AND YEAR(l.created_at) = $filter_year";
} elseif ($filter_month > 0 && $filter_year > 0) {
    $where .= " AND MONTH(l.created_at) = $filter_month AND YEAR(l.created_at) = $filter_year";
} elseif ($filter_year > 0) {
    $where .= " AND YEAR(l.created_at) = $filter_year";
}

// Query dengan JOIN untuk mengambil nama user
$query = "SELECT l.*, u.nama_lengkap 
          FROM log_aktivitas l 
          JOIN users u ON l.id_user = u.id_user 
          WHERE $where
          ORDER BY l.created_at DESC";
$logs = mysqli_query($conn, $query);
$total_logs = mysqli_num_rows($logs);

// Ambil tahun yang unik untuk dropdown
$years_query = mysqli_query($conn, "SELECT DISTINCT YEAR(created_at) as year FROM log_aktivitas ORDER BY year DESC");
?>

<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="flex items-center gap-3">
        <div class="p-2 bg-primary/10 rounded-lg">
            <i class="fa-solid fa-history text-primary text-xl"></i>
        </div>
        <div>
            <h2 class="text-2xl font-bold">Log Aktivitas Sistem</h2>
            <p class="text-xs opacity-50"><?php echo $total_logs; ?> catatan ditemukan</p>
        </div>
    </div>
    <a href="dashboard.php" class="btn btn-ghost btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<!-- Filter Form -->
<div class="card bg-base-100 shadow-xl mb-6">
    <div class="card-body py-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="form-control">
                <label class="label py-1"><span class="label-text text-xs font-bold uppercase">Hari</span></label>
                <select name="day" class="select select-bordered select-sm w-24">
                    <option value="">Semua</option>
                    <?php for($d = 1; $d <= 31; $d++): ?>
                    <option value="<?php echo $d; ?>" <?php if($filter_day == $d) echo 'selected'; ?>><?php echo $d; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-control">
                <label class="label py-1"><span class="label-text text-xs font-bold uppercase">Bulan</span></label>
                <select name="month" class="select select-bordered select-sm w-32">
                    <option value="">Semua</option>
                    <?php 
                    $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    for($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo $m; ?>" <?php if($filter_month == $m) echo 'selected'; ?>><?php echo $months[$m-1]; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-control">
                <label class="label py-1"><span class="label-text text-xs font-bold uppercase">Tahun</span></label>
                <select name="year" class="select select-bordered select-sm w-28">
                    <option value="">Semua</option>
                    <?php while($y = mysqli_fetch_assoc($years_query)): ?>
                    <option value="<?php echo $y['year']; ?>" <?php if($filter_year == $y['year']) echo 'selected'; ?>><?php echo $y['year']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            <a href="activity_log.php" class="btn btn-ghost btn-sm">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </a>
        </form>
    </div>
</div>

<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th class="bg-base-200">Waktu Kejadian</th>
                        <th class="bg-base-200">Pelaku (User)</th>
                        <th class="bg-base-200">Detail Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($total_logs > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($logs)): ?>
                        <tr class="hover">
                            <td class="font-mono text-xs opacity-70">
                                <?php echo date('d M Y, H:i:s', strtotime($row['created_at'])); ?>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral-focus text-neutral-content rounded-full w-6">
                                            <span class="text-[10px]"><?php echo substr($row['nama_lengkap'], 0, 1); ?></span>
                                        </div>
                                    </div>
                                    <span class="font-bold"><?php echo $row['nama_lengkap']; ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-ghost badge-sm py-3 px-4 italic">
                                    <?php echo $row['aktivitas']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-20">
                                <i class="fa-solid fa-clock-rotate-left text-5xl opacity-10 mb-4 block"></i>
                                <p class="opacity-50 italic">Tidak ada catatan aktivitas untuk filter ini.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/sidebar.php'; ?>
