<?php
require_once '../includes/header.php';

// Fetch Statistics with error handling
$res_total = mysqli_query($conn, "SELECT COUNT(*) as count FROM data_customer WHERE deleted_at IS NULL");
$total_customer = ($res_total) ? mysqli_fetch_assoc($res_total)['count'] : 0;

$res_user = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE status_akun = 'aktif'");
$total_user = ($res_user) ? mysqli_fetch_assoc($res_user)['count'] : 0;

$res_prospek = mysqli_query($conn, "SELECT COUNT(*) as count FROM data_customer WHERE status_customer = 'Prospek' AND deleted_at IS NULL");
$total_prospek = ($res_prospek) ? mysqli_fetch_assoc($res_prospek)['count'] : 0;

$res_aktif = mysqli_query($conn, "SELECT COUNT(*) as count FROM data_customer WHERE status_customer = 'Aktif' AND deleted_at IS NULL");
$total_aktif = ($res_aktif) ? mysqli_fetch_assoc($res_aktif)['count'] : 0;

// Activity Logs (Top 5)
$logs = mysqli_query($conn, "SELECT l.*, u.nama_lengkap FROM log_aktivitas l JOIN users u ON l.id_user = u.id_user ORDER BY l.created_at DESC LIMIT 5");
?>

<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight">Dashboard Overview</h1>
        <p class="text-base-content/60">Selamat datang kembali, <span class="font-semibold text-primary"><?php echo $_SESSION['nama_lengkap']; ?></span>!</p>
    </div>
    <div class="text-sm px-4 py-2 bg-base-100 rounded-lg shadow-sm border border-base-300 font-mono">
        <i class="fa-solid fa-calendar-alt mr-2 text-primary"></i><?php echo date('d M Y'); ?>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stats shadow bg-base-100 border-b-4 border-primary overflow-hidden transition-transform hover:scale-[1.02]">
        <div class="stat">
            <div class="stat-figure text-primary opacity-20">
                <i class="fa-solid fa-users text-4xl"></i>
            </div>
            <div class="stat-title text-xs font-bold uppercase tracking-wider">Total Customer</div>
            <div class="stat-value text-primary"><?php echo $total_customer; ?></div>
            <div class="stat-desc text-[10px]">Database Aktif</div>
        </div>
    </div>
    
    <div class="stats shadow bg-base-100 border-b-4 border-secondary overflow-hidden transition-transform hover:scale-[1.02]">
        <div class="stat">
            <div class="stat-figure text-secondary opacity-20">
                <i class="fa-solid fa-user-shield text-4xl"></i>
            </div>
            <div class="stat-title text-xs font-bold uppercase tracking-wider">Total Staff</div>
            <div class="stat-value text-secondary"><?php echo $total_user; ?></div>
            <div class="stat-desc text-[10px]">User Aktif</div>
        </div>
    </div>

    <div class="stats shadow bg-base-100 border-b-4 border-accent overflow-hidden transition-transform hover:scale-[1.02]">
        <div class="stat">
            <div class="stat-figure text-accent opacity-20">
                <i class="fa-solid fa-bolt text-4xl"></i>
            </div>
            <div class="stat-title text-xs font-bold uppercase tracking-wider">Prospek Hot</div>
            <div class="stat-value text-accent"><?php echo $total_prospek; ?></div>
            <div class="stat-desc text-[10px]">Menunggu Follow Up</div>
        </div>
    </div>

    <div class="stats shadow bg-base-100 border-b-4 border-info overflow-hidden transition-transform hover:scale-[1.02]">
        <div class="stat">
            <div class="stat-figure text-info opacity-20">
                <i class="fa-solid fa-check-circle text-4xl"></i>
            </div>
            <div class="stat-title text-xs font-bold uppercase tracking-wider">Customer Aktif</div>
            <div class="stat-value text-info"><?php echo $total_aktif; ?></div>
            <div class="stat-desc text-[10px]">Pelanggan Terverifikasi</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Chart Section -->
    <div class="lg:col-span-2 card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <h2 class="card-title text-lg">Visualisasi Status Customer</h2>
                <div class="hidden sm:flex badge badge-outline gap-2 p-4 text-[10px] uppercase font-bold">Real-time Data</div>
            </div>
            <div class="h-[300px]">
                <canvas id="customerChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <h2 class="card-title text-lg mb-6 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                Aktivitas Terakhir
            </h2>
            <div class="space-y-4">
                <?php if(mysqli_num_rows($logs) > 0): ?>
                    <?php while($log = mysqli_fetch_assoc($logs)): ?>
                    <div class="flex items-start gap-3 pb-3 border-b border-base-200 last:border-0 hover:bg-base-200/50 p-1 rounded transition-colors">
                        <div class="avatar placeholder">
                            <div class="bg-primary/10 text-primary rounded-lg w-8 h-8">
                                <span class="text-xs font-bold"><?php echo substr($log['nama_lengkap'], 0, 1); ?></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold"><?php echo $log['nama_lengkap']; ?></p>
                            <p class="text-[11px] opacity-70 line-clamp-1 italic">"<?php echo $log['aktivitas']; ?>"</p>
                            <p class="text-[9px] opacity-40 mt-1"><?php echo date('H:i', strtotime($log['created_at'])); ?> • <?php echo date('d M', strtotime($log['created_at'])); ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-10 opacity-30">
                        <i class="fa-solid fa-inbox text-4xl block mb-2"></i>
                        <p class="text-sm">Belum ada aktivitas</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($_SESSION['role'] == 'admin'): ?>
            <div class="card-actions justify-end mt-4">
                <a href="activity_log.php" class="btn btn-sm btn-ghost hover:btn-primary hover:text-white transition-all gap-2 group">
                    Lihat Semua
                    <i class="fa-solid fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('customerChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', // Using Bar chart but with custom styling
        data: {
            labels: ['Prospek', 'Aktif', 'Tidak Aktif'],
            datasets: [{
                label: 'Jumlah Customer',
                data: [
                    <?php echo $total_prospek; ?>, 
                    <?php echo $total_aktif; ?>, 
                    <?php echo ($total_customer - $total_prospek - $total_aktif); ?>
                ],
                backgroundColor: [
                    'rgba(87, 13, 248, 0.7)',  // Primary
                    'rgba(240, 0, 184, 0.7)',  // Secondary
                    'rgba(55, 205, 190, 0.7)'  // Accent
                ],
                borderColor: [
                    '#570df8',
                    '#f000b8',
                    '#37cdbe'
                ],
                borderWidth: 2,
                borderRadius: 8,
                barThickness: 50
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { font: { size: 10 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: 'bold', size: 11 } }
                }
            }
        }
    });
</script>

<?php require_once '../includes/sidebar.php'; ?>
