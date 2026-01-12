<?php
require_once '../includes/header.php';

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM data_customer WHERE id_customer = '$id'";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

if (!$customer) {
    setFlash('error', 'Customer tidak ditemukan!');
    header("Location: customer_list.php");
    exit();
}

$followups = mysqli_query($conn, "SELECT * FROM follow_up WHERE id_customer = '$id' ORDER BY tanggal_followup DESC");
?>

<!-- Back Button - More touch-friendly for mobile -->
<div class="mb-4 sm:mb-6">
    <a href="customer_list.php" class="btn btn-ghost btn-sm sm:btn-md gap-2">
        <i class="fa-solid fa-arrow-left"></i> 
        <span class="hidden sm:inline">Kembali ke Daftar</span>
        <span class="sm:hidden">Kembali</span>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
    <!-- Customer Info -->
    <div class="lg:col-span-1">
        <div class="card bg-base-100 shadow-xl mb-4 sm:mb-6">
            <div class="card-body p-4 sm:p-6">
                <div class="flex flex-col items-center text-center mb-4">
                    <div class="avatar placeholder mb-2">
                        <div class="bg-neutral-focus text-neutral-content rounded-full w-16 sm:w-24">
                            <span class="text-xl sm:text-3xl"><?php echo substr($customer['nama_customer'], 0, 1); ?></span>
                        </div>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold break-words max-w-full"><?php echo $customer['nama_customer']; ?></h2>
                    <div class="badge badge-primary mt-2"><?php echo $customer['status_customer']; ?></div>
                </div>
                
                <div class="space-y-3 sm:space-y-4">
                    <div class="bg-base-200/50 p-3 rounded-lg">
                        <p class="text-[10px] sm:text-xs uppercase font-bold opacity-50 mb-1">Email</p>
                        <p class="text-sm sm:text-base break-all"><?php echo $customer['email'] ?: '-'; ?></p>
                    </div>
                    <div class="bg-base-200/50 p-3 rounded-lg">
                        <p class="text-[10px] sm:text-xs uppercase font-bold opacity-50 mb-1">Telepon</p>
                        <p class="text-sm sm:text-base"><?php echo $customer['no_telepon'] ?: '-'; ?></p>
                    </div>
                    <div class="bg-base-200/50 p-3 rounded-lg">
                        <p class="text-[10px] sm:text-xs uppercase font-bold opacity-50 mb-1">Alamat</p>
                        <p class="text-sm"><?php echo $customer['alamat'] ?: '-'; ?></p>
                    </div>
                    <div class="bg-base-200/50 p-3 rounded-lg">
                        <p class="text-[10px] sm:text-xs uppercase font-bold opacity-50 mb-1">Sumber</p>
                        <p class="text-sm sm:text-base"><?php echo $customer['sumber_customer']; ?></p>
                    </div>
                    <div class="bg-base-200/50 p-3 rounded-lg">
                        <p class="text-[10px] sm:text-xs uppercase font-bold opacity-50 mb-1">Catatan Internal</p>
                        <p class="text-xs sm:text-sm italic"><?php echo $customer['catatan'] ?: 'Tidak ada catatan.'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Follow Up Section -->
    <div class="lg:col-span-2">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-4 sm:mb-6">
                    <h3 class="text-base sm:text-lg font-bold">Riwayat Follow Up</h3>
                    <button class="btn btn-sm sm:btn-md btn-primary w-full sm:w-auto" onclick="followup_modal.showModal()">
                        <i class="fa-solid fa-plus"></i> Tambah Follow Up
                    </button>
                </div>

                <div class="relative border-l-2 border-base-300 ml-2 sm:ml-3 pl-4 sm:pl-6 space-y-4 sm:space-y-6">
                    <?php if(mysqli_num_rows($followups) > 0): ?>
                        <?php while($f = mysqli_fetch_assoc($followups)): ?>
                        <div class="relative">
                            <div class="absolute -left-[23px] sm:-left-[31px] top-0 w-3 h-3 sm:w-4 sm:h-4 rounded-full <?php echo $f['status_followup'] == 'Selesai' ? 'bg-success' : ($f['status_followup'] == 'Terjadwal' ? 'bg-info' : 'bg-error'); ?>"></div>
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2 sm:gap-4 bg-base-200/30 p-3 rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <p class="font-bold text-sm"><?php echo date('d M Y', strtotime($f['tanggal_followup'])); ?></p>
                                        <span class="badge badge-sm <?php echo $f['status_followup'] == 'Selesai' ? 'badge-success' : ($f['status_followup'] == 'Terjadwal' ? 'badge-info' : 'badge-error'); ?>"><?php echo $f['status_followup']; ?></span>
                                    </div>
                                    <p class="text-sm break-words"><?php echo $f['catatan']; ?></p>
                                </div>
                                <div class="flex gap-1 self-end sm:self-start">
                                    <a href="../process/follow_up_process.php?action=delete&id=<?php echo $f['id_followup']; ?>&cid=<?php echo $id; ?>" class="btn btn-sm btn-ghost text-error" onclick="return confirm('Hapus log?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fa-solid fa-clipboard-list text-4xl opacity-30 mb-2"></i>
                            <p class="text-sm opacity-50 italic">Belum ada riwayat follow up.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Follow Up - Full width on mobile -->
<dialog id="followup_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-full sm:max-w-lg">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Catat Follow Up</h3>
        <form action="../process/follow_up_process.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id_customer" value="<?php echo $id; ?>">
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Tanggal</span></label>
                <input type="date" name="tanggal_followup" class="input input-bordered w-full" required value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text font-medium">Status</span></label>
                <select name="status_followup" class="select select-bordered w-full">
                    <option value="Terjadwal">Terjadwal</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text font-medium">Catatan Aktivitas</span></label>
                <textarea name="catatan" class="textarea textarea-bordered h-28" placeholder="Apa yang dibicarakan?" required></textarea>
            </div>
            <div class="modal-action flex-col sm:flex-row gap-2 sm:gap-0">
                <button type="button" class="btn btn-ghost w-full sm:w-auto order-2 sm:order-1" onclick="followup_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary w-full sm:w-auto order-1 sm:order-2">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<?php require_once '../includes/sidebar.php'; ?>
