<?php
require_once '../includes/header.php';

// Filter & Search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$sumber = isset($_GET['sumber']) ? mysqli_real_escape_string($conn, $_GET['sumber']) : '';

$where = "WHERE deleted_at IS NULL";
if ($search) $where .= " AND (nama_customer LIKE '%$search%' OR email LIKE '%$search%')";
if ($status) $where .= " AND status_customer = '$status'";
if ($sumber) $where .= " AND sumber_customer = '$sumber'";

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$total_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM data_customer $where");
$total_data = mysqli_fetch_assoc($total_query)['count'];
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM data_customer $where ORDER BY created_at DESC LIMIT $start, $limit";
$customers = mysqli_query($conn, $query);
?>

<!-- Header Section - Mobile Optimized -->
<div class="flex flex-col gap-4 mb-4 sm:mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h2 class="text-xl sm:text-2xl font-bold">Data Customer</h2>

        <!-- Mobile: Compact Buttons -->
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button class="btn btn-sm sm:btn-md btn-primary flex-1 sm:flex-none" onclick="add_modal.showModal()">
                <i class="fa-solid fa-plus"></i> <span class="hidden xs:inline">Tambah</span>
            </button>
            <button class="btn btn-sm sm:btn-md btn-outline flex-1 sm:flex-none" onclick="import_modal.showModal()">
                <i class="fa-solid fa-file-import"></i> <span class="hidden xs:inline">Import</span>
            </button>
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-sm sm:btn-md btn-outline">
                    <i class="fa-solid fa-download"></i>
                    <span class="hidden sm:inline ml-1">Export</span>
                    <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
                </label>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="../process/export_csv.php"><i class="fa-solid fa-file-csv"></i> Export to CSV</a></li>
                    <li><a href="../process/export_excel.php" class="text-success font-bold"><i class="fa-solid fa-file-excel"></i> Export to Excel</a></li>
                    <li><button type="button" onclick="exportPDF()" class="text-error font-bold"><i class="fa-solid fa-file-pdf"></i> Export to PDF</button></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card bg-base-100 shadow-xl overflow-visible">
    <div class="card-body p-3 sm:p-6">
        <!-- Filter and Bulk Action Form - Mobile Optimized -->
        <div class="flex flex-col gap-3 mb-4 sm:mb-6">
            <form method="GET" class="flex flex-col gap-3">
                <!-- Search Input - Full Width on Mobile -->
                <label class="input input-bordered input-sm sm:input-md w-full flex items-center gap-2">
                    <i class="fa-solid fa-search opacity-50"></i>
                    <input type="text" name="search" id="realtime-search" placeholder="Cari nama atau email customer..."
                        class="grow bg-transparent border-none outline-none"
                        value="<?php echo htmlspecialchars($search); ?>" autocomplete="off">
                </label>

                <!-- Filters & Actions Row -->
                <div class="flex flex-wrap gap-2">
                    <!-- Status Filter -->
                    <select name="status" class="select select-bordered select-sm sm:select-md flex-1 min-w-[120px] max-w-[160px]" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Prospek" <?php if ($status == 'Prospek') echo 'selected'; ?>>Prospek</option>
                        <option value="Aktif" <?php if ($status == 'Aktif') echo 'selected'; ?>>Aktif</option>
                        <option value="Tidak Aktif" <?php if ($status == 'Tidak Aktif') echo 'selected'; ?>>Tidak Aktif</option>
                    </select>

                    <!-- Sumber Filter -->
                    <select name="sumber" class="select select-bordered select-sm sm:select-md flex-1 min-w-[120px] max-w-[160px]" onchange="this.form.submit()">
                        <option value="">Semua Sumber</option>
                        <option value="Email" <?php if ($sumber == 'Email') echo 'selected'; ?>>Email</option>
                        <option value="Media Sosial" <?php if ($sumber == 'Media Sosial') echo 'selected'; ?>>Media Sosial</option>
                        <option value="Telepon" <?php if ($sumber == 'Telepon') echo 'selected'; ?>>Telepon</option>
                    </select>

                    <!-- Action Buttons - Right aligned -->
                    <div class="flex gap-2 ml-auto">
                        <button type="submit" class="btn btn-sm sm:btn-md btn-primary" title="Cari">
                            <i class="fa-solid fa-search"></i>
                            <span class="hidden md:inline ml-1">Cari</span>
                        </button>
                        <a href="customer_list.php" class="btn btn-sm sm:btn-md btn-ghost" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span class="hidden md:inline ml-1">Reset</span>
                        </a>
                    </div>
                </div>
            </form>

            <!-- Bulk Action Button (Visible when items selected) -->
            <div id="bulk-action-container" class="hidden">
                <button type="button" class="btn btn-sm sm:btn-md btn-error w-full sm:w-auto" onclick="confirmBulkDelete()">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Hapus Terpilih (<span id="selected-count">0</span>)</span>
                </button>
            </div>
        </div>

        <form id="bulk-form" action="../process/customer_process.php" method="POST">
            <input type="hidden" name="action" value="bulk_delete">

            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead>
                        <tr>
                            <th class="w-10">
                                <input type="checkbox" id="select-all" class="checkbox checkbox-primary checkbox-sm">
                            </th>
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Sumber</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="customer-table-body">
                        <?php if (mysqli_num_rows($customers) > 0): ?>
                            <?php mysqli_data_seek($customers, 0); ?>
                            <?php while ($row = mysqli_fetch_assoc($customers)): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="customer_ids[]" value="<?php echo $row['id_customer']; ?>" class="checkbox checkbox-sm customer-checkbox">
                                    </td>
                                    <td>
                                        <div class="font-bold"><?php echo $row['nama_customer']; ?></div>
                                        <div class="text-[10px] opacity-50 uppercase"><?php echo date('d M Y', strtotime($row['tanggal_daftar'])); ?></div>
                                    </td>
                                    <td>
                                        <div class="text-xs"><?php echo $row['email']; ?></div>
                                        <div class="text-xs"><?php echo $row['no_telepon']; ?></div>
                                    </td>
                                    <td>
                                        <div class="badge badge-ghost text-[10px] uppercase"><?php echo $row['sumber_customer']; ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $badge = 'badge-ghost';
                                        if ($row['status_customer'] == 'Aktif') $badge = 'badge-success';
                                        if ($row['status_customer'] == 'Prospek') $badge = 'badge-info';
                                        if ($row['status_customer'] == 'Tidak Aktif') $badge = 'badge-error';
                                        ?>
                                        <div class="badge <?php echo $badge; ?> font-semibold text-[10px] uppercase"><?php echo $row['status_customer']; ?></div>
                                    </td>
                                    <td>
                                        <div class="flex gap-1">
                                            <button type="button" class="btn btn-xs btn-info" onclick='editCustomer(<?php echo json_encode($row); ?>)'>
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <a href="../process/customer_process.php?action=delete&id=<?php echo $row['id_customer']; ?>" class="btn btn-xs btn-error" onclick="return confirm('Hapus customer ini (Soft Delete)?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                            <a href="customer_detail.php?id=<?php echo $row['id_customer']; ?>" class="btn btn-xs btn-ghost">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center opacity-50 italic py-10">Data tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="sm:hidden space-y-3" id="customer-card-view">
                <!-- Select All for Mobile -->
                <label class="flex items-center gap-2 p-2 bg-base-200 rounded-lg cursor-pointer">
                    <input type="checkbox" id="select-all-mobile" class="checkbox checkbox-primary checkbox-sm">
                    <span class="text-sm font-medium">Pilih Semua</span>
                </label>

                <?php
                mysqli_data_seek($customers, 0);
                if (mysqli_num_rows($customers) > 0):
                    while ($row = mysqli_fetch_assoc($customers)):
                        $badge = 'badge-ghost';
                        if ($row['status_customer'] == 'Aktif') $badge = 'badge-success';
                        if ($row['status_customer'] == 'Prospek') $badge = 'badge-info';
                        if ($row['status_customer'] == 'Tidak Aktif') $badge = 'badge-error';
                ?>
                        <div class="bg-base-200/50 rounded-xl p-4 space-y-3">
                            <!-- Header with checkbox and name -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" name="customer_ids[]" value="<?php echo $row['id_customer']; ?>" class="checkbox checkbox-sm customer-checkbox mt-1">
                                    <div>
                                        <h3 class="font-bold text-base"><?php echo $row['nama_customer']; ?></h3>
                                        <p class="text-xs opacity-50"><?php echo date('d M Y', strtotime($row['tanggal_daftar'])); ?></p>
                                    </div>
                                </div>
                                <div class="badge <?php echo $badge; ?> font-semibold text-[10px] uppercase shrink-0">
                                    <?php echo $row['status_customer']; ?>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="grid grid-cols-1 gap-2 text-sm pl-8">
                                <?php if ($row['email']): ?>
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-envelope text-xs opacity-50 w-4"></i>
                                        <span class="truncate"><?php echo $row['email']; ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($row['no_telepon']): ?>
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-phone text-xs opacity-50 w-4"></i>
                                        <span><?php echo $row['no_telepon']; ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-tag text-xs opacity-50 w-4"></i>
                                    <span class="badge badge-ghost badge-sm"><?php echo $row['sumber_customer']; ?></span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-2 border-t border-base-300">
                                <button type="button" class="btn btn-sm btn-info flex-1" onclick='editCustomer(<?php echo json_encode($row); ?>)'>
                                    <i class="fa-solid fa-edit"></i> Edit
                                </button>
                                <a href="customer_detail.php?id=<?php echo $row['id_customer']; ?>" class="btn btn-sm btn-ghost flex-1">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </a>
                                <a href="../process/customer_process.php?action=delete&id=<?php echo $row['id_customer']; ?>"
                                    class="btn btn-sm btn-error btn-square"
                                    onclick="return confirm('Hapus customer ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-10 opacity-50">
                        <i class="fa-solid fa-users-slash text-4xl mb-2"></i>
                        <p class="italic">Data tidak ditemukan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </form>

        <!-- Pagination - Mobile Optimized -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mt-4 sm:mt-6 pt-4 border-t border-base-200">
            <p class="text-xs sm:text-sm opacity-60 order-2 sm:order-1">
                Menampilkan <?php echo min($start + 1, $total_data); ?>-<?php echo min($start + $limit, $total_data); ?> dari <?php echo $total_data; ?> data
            </p>
            <div class="join order-1 sm:order-2">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>&status=<?php echo $status; ?>&sumber=<?php echo $sumber; ?>"
                        class="join-item btn btn-sm">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <?php
                // Show limited pages on mobile
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                if ($start_page > 1): ?>
                    <a href="?page=1&search=<?php echo $search; ?>&status=<?php echo $status; ?>&sumber=<?php echo $sumber; ?>"
                        class="join-item btn btn-sm">1</a>
                    <?php if ($start_page > 2): ?>
                        <span class="join-item btn btn-sm btn-disabled">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>&status=<?php echo $status; ?>&sumber=<?php echo $sumber; ?>"
                        class="join-item btn btn-sm <?php echo ($page == $i) ? 'btn-active btn-primary' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($end_page < $total_pages): ?>
                    <?php if ($end_page < $total_pages - 1): ?>
                        <span class="join-item btn btn-sm btn-disabled">...</span>
                    <?php endif; ?>
                    <a href="?page=<?php echo $total_pages; ?>&search=<?php echo $search; ?>&status=<?php echo $status; ?>&sumber=<?php echo $sumber; ?>"
                        class="join-item btn btn-sm"><?php echo $total_pages; ?></a>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>&status=<?php echo $status; ?>&sumber=<?php echo $sumber; ?>"
                        class="join-item btn btn-sm">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah - Mobile Optimized -->
<dialog id="add_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-full sm:w-11/12 sm:max-w-2xl max-h-[90vh]">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Tambah Customer Baru</h3>
        <form action="../process/customer_process.php" method="POST" id="form-add">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div class="form-control w-full sm:col-span-2">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Nama Customer</span></label>
                    <input type="text" name="nama_customer" class="input input-bordered input-sm sm:input-md w-full" required>
                </div>
                <div class="form-control w-full">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Email</span></label>
                    <input type="email" name="email" class="input input-bordered input-sm sm:input-md w-full">
                </div>
                <div class="form-control w-full">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Telepon</span></label>
                    <input type="text" name="no_telepon" class="input input-bordered input-sm sm:input-md w-full">
                </div>
                <div class="form-control w-full">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Sumber</span></label>
                    <select name="sumber_customer" class="select select-bordered select-sm sm:select-md w-full">
                        <option value="Email">Email</option>
                        <option value="Media Sosial">Media Sosial</option>
                        <option value="Telepon">Telepon</option>
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Status</span></label>
                    <select name="status_customer" class="select select-bordered select-sm sm:select-md w-full">
                        <option value="Prospek">Prospek</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="form-control sm:col-span-2">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Alamat</span></label>
                    <textarea name="alamat" class="textarea textarea-bordered textarea-sm sm:textarea-md h-16 sm:h-20"></textarea>
                </div>
                <div class="form-control sm:col-span-2">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Catatan</span></label>
                    <textarea name="catatan" class="textarea textarea-bordered textarea-sm sm:textarea-md h-16 sm:h-20"></textarea>
                </div>
            </div>
            <div class="modal-action flex-col-reverse sm:flex-row gap-2 sm:gap-0 mt-4">
                <button type="button" class="btn btn-ghost w-full sm:w-auto" onclick="add_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary w-full sm:w-auto sm:px-10">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Modal Edit - Mobile Optimized -->
<dialog id="edit_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-full sm:w-11/12 sm:max-w-2xl max-h-[90vh]">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Edit Data Customer</h3>
        <form action="../process/customer_process.php" method="POST" id="form-edit">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_customer" id="edit_id">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div class="form-control w-full sm:col-span-2">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Nama Customer</span></label>
                    <input type="text" name="nama_customer" id="edit_nama" class="input input-bordered input-sm sm:input-md w-full" required>
                </div>
                <div class="form-control w-full">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Email</span></label>
                    <input type="email" name="email" id="edit_email" class="input input-bordered input-sm sm:input-md w-full">
                </div>
                <div class="form-control w-full">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Telepon</span></label>
                    <input type="text" name="no_telepon" id="edit_telepon" class="input input-bordered input-sm sm:input-md w-full">
                </div>
                <div class="form-control w-full">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Sumber</span></label>
                    <select name="sumber_customer" id="edit_sumber" class="select select-bordered select-sm sm:select-md w-full">
                        <option value="Email">Email</option>
                        <option value="Media Sosial">Media Sosial</option>
                        <option value="Telepon">Telepon</option>
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Status</span></label>
                    <select name="status_customer" id="edit_status" class="select select-bordered select-sm sm:select-md w-full">
                        <option value="Prospek">Prospek</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="form-control sm:col-span-2">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Alamat</span></label>
                    <textarea name="alamat" id="edit_alamat" class="textarea textarea-bordered textarea-sm sm:textarea-md h-16 sm:h-20"></textarea>
                </div>
                <div class="form-control sm:col-span-2">
                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase">Catatan</span></label>
                    <textarea name="catatan" id="edit_catatan" class="textarea textarea-bordered textarea-sm sm:textarea-md h-16 sm:h-20"></textarea>
                </div>
            </div>
            <div class="modal-action flex-col-reverse sm:flex-row gap-2 sm:gap-0 mt-4">
                <button type="button" class="btn btn-ghost w-full sm:w-auto" onclick="edit_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary w-full sm:w-auto sm:px-10">Update</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Modal Import - Mobile Optimized -->
<dialog id="import_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-full sm:max-w-lg">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-2">Import via CSV</h3>
        <p class="text-xs opacity-60 mb-4">Pastikan kolom CSV: ID, Nama, Email, Telepon, Sumber, Status.</p>
        <form action="../process/import_csv.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="csv_file" class="file-input file-input-bordered file-input-sm sm:file-input-md w-full" accept=".csv" required />
            <div class="modal-action flex-col-reverse sm:flex-row gap-2 sm:gap-0 mt-4">
                <button type="button" class="btn btn-ghost w-full sm:w-auto" onclick="import_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary w-full sm:w-auto">Import Sekarang</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    function editCustomer(data) {
        document.getElementById('edit_id').value = data.id_customer;
        document.getElementById('edit_nama').value = data.nama_customer;
        document.getElementById('edit_email').value = data.email;
        document.getElementById('edit_telepon').value = data.no_telepon;
        document.getElementById('edit_sumber').value = data.sumber_customer;
        document.getElementById('edit_status').value = data.status_customer;
        document.getElementById('edit_alamat').value = data.alamat;
        document.getElementById('edit_catatan').value = data.catatan;
        edit_modal.showModal();
    }

    // Bulk Selection Logic
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.customer-checkbox');
    const bulkActionContainer = document.getElementById('bulk-action-container');
    const selectedCountLabel = document.getElementById('selected-count');

    function updateBulkUI() {
        const selectedCount = document.querySelectorAll('.customer-checkbox:checked').length;
        if (selectedCount > 0) {
            bulkActionContainer.classList.remove('hidden');
            selectedCountLabel.innerText = selectedCount;
        } else {
            bulkActionContainer.classList.add('hidden');
        }
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        // Sync mobile select-all
        const mobileSelectAll = document.getElementById('select-all-mobile');
        if (mobileSelectAll) mobileSelectAll.checked = this.checked;
        updateBulkUI();
    });

    // Mobile select-all handler
    const mobileSelectAll = document.getElementById('select-all-mobile');
    if (mobileSelectAll) {
        mobileSelectAll.addEventListener('change', function() {
            document.querySelectorAll('.customer-checkbox').forEach(cb => cb.checked = this.checked);
            if (selectAll) selectAll.checked = this.checked;
            updateBulkUI();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkUI);
    });

    // Real-time Search Logic
    const searchInput = document.getElementById('realtime-search');
    const tableBody = document.getElementById('customer-table-body');
    const statusSelect = document.querySelector('select[name="status"]');
    const sumberSelect = document.querySelector('select[name="sumber"]');
    let timeout = null;

    function performSearch() {
        const searchValue = searchInput.value;
        const statusValue = statusSelect.value;
        const sumberValue = sumberSelect.value;

        // Show loading state (optional)
        tableBody.style.opacity = '0.5';

        fetch(`../process/search_customer_ajax.php?search=${searchValue}&status=${statusValue}&sumber=${sumberValue}`)
            .then(response => response.text())
            .then(data => {
                tableBody.innerHTML = data;
                tableBody.style.opacity = '1';
                // Re-bind checkbox listeners after content update
                rebindCheckboxes();
                updateBulkUI();
            });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(performSearch, 300); // Debounce 300ms
    });

    // Update real-time when selects change (optional, currently reloads page)
    statusSelect.onchange = performSearch;
    sumberSelect.onchange = performSearch;

    function rebindCheckboxes() {
        const newCheckboxes = document.querySelectorAll('.customer-checkbox');
        newCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkUI);
        });
    }

    function confirmBulkDelete() {
        if (confirm('Hapus semua customer yang terpilih (Soft Delete)?')) {
            document.getElementById('bulk-form').submit();
        }
    }

    // Validasi Form
    document.querySelectorAll('form').forEach(form => {
        if (form.id !== 'bulk-form') {
            form.addEventListener('submit', function(e) {
                const requiredFields = this.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (field.value.trim() === '') {
                        e.preventDefault();
                        field.classList.add('input-error');
                    } else {
                        field.classList.remove('input-error');
                    }
                });
            });
        }
    });
</script>

<!-- Library untuk PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- Template Hidden untuk PDF -->
<div id="pdf-template" class="hidden">
    <div style="padding: 20px; font-family: Arial, sans-serif;">
        <h1 style="text-align: center; color: #570df8;">LAPORAN DATA CUSTOMER</h1>
        <p style="text-align: center; font-size: 12px; margin-bottom: 20px;">Dicetak pada: <?php echo date('d M Y, H:i'); ?></p>

        <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
            <thead>
                <tr style="background-color: #570df8; color: white;">
                    <th style="border: 1px solid #ddd; padding: 8px;">No</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Nama Customer</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Email</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Telepon</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Sumber</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pdf_query = "SELECT * FROM data_customer WHERE deleted_at IS NULL ORDER BY created_at DESC";
                $pdf_res = mysqli_query($conn, $pdf_query);
                $no = 1;
                while ($p = mysqli_fetch_assoc($pdf_res)):
                ?>
                    <tr style="color: black;">
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><?php echo $no++; ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $p['nama_customer']; ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $p['email']; ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $p['no_telepon']; ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $p['sumber_customer']; ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center; font-weight: bold;"><?php echo $p['status_customer']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function exportPDF() {
        const element = document.getElementById('pdf-template');
        element.classList.remove('hidden'); // Tampilkan sebentar untuk diproses

        const opt = {
            margin: 0.5,
            filename: 'Laporan_Customer_<?php echo date('Ymd'); ?>.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            element.classList.add('hidden'); // Sembunyikan kembali
        });
    }
</script>

<?php require_once '../includes/sidebar.php'; ?>