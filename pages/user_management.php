<?php
require_once '../includes/header.php';
checkAdmin();

// Search & Filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$role_filter = isset($_GET['role']) ? mysqli_real_escape_string($conn, $_GET['role']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

$where = "1=1";
if ($search) $where .= " AND (nama_lengkap LIKE '%$search%' OR username LIKE '%$search%' OR email LIKE '%$search%')";
if ($role_filter) $where .= " AND role = '$role_filter'";
if ($status_filter) $where .= " AND status_akun = '$status_filter'";

$query = "SELECT * FROM users WHERE $where ORDER BY created_at DESC";
$users = mysqli_query($conn, $query);
$total_users = mysqli_num_rows($users);
?>

<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="flex items-center gap-3">
        <div class="p-2 bg-primary/10 rounded-lg">
            <i class="fa-solid fa-users-gear text-primary text-xl"></i>
        </div>
        <div>
            <h2 class="text-2xl font-bold">Manajemen User</h2>
            <p class="text-xs opacity-50"><?php echo $total_users; ?> user ditemukan</p>
        </div>
    </div>
    <button class="btn btn-primary" onclick="add_user_modal.showModal()">
        <i class="fa-solid fa-user-plus"></i> Tambah User
    </button>
</div>

<!-- Search & Filter -->
<div class="card bg-base-100 shadow-xl mb-6">
    <div class="card-body py-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="form-control flex-1 min-w-[200px]">
                <label class="label py-1"><span class="label-text text-xs font-bold uppercase">Cari</span></label>
                <input type="text" name="search" placeholder="Nama, username, atau email..." 
                       class="input input-bordered input-sm" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="form-control">
                <label class="label py-1"><span class="label-text text-xs font-bold uppercase">Role</span></label>
                <select name="role" class="select select-bordered select-sm w-28">
                    <option value="">Semua</option>
                    <option value="admin" <?php if($role_filter == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="staff" <?php if($role_filter == 'staff') echo 'selected'; ?>>Staff</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label py-1"><span class="label-text text-xs font-bold uppercase">Status</span></label>
                <select name="status" class="select select-bordered select-sm w-28">
                    <option value="">Semua</option>
                    <option value="aktif" <?php if($status_filter == 'aktif') echo 'selected'; ?>>Aktif</option>
                    <option value="nonaktif" <?php if($status_filter == 'nonaktif') echo 'selected'; ?>>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-search"></i> Cari
            </button>
            <a href="user_management.php" class="btn btn-ghost btn-sm">
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
                        <th class="bg-base-200">Nama</th>
                        <th class="bg-base-200">Username</th>
                        <th class="bg-base-200">Email</th>
                        <th class="bg-base-200">Role</th>
                        <th class="bg-base-200">Status</th>
                        <th class="bg-base-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($total_users > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($users)): ?>
                        <tr class="hover">
                            <td class="font-bold"><?php echo $row['nama_lengkap']; ?></td>
                            <td class="font-mono text-xs"><?php echo $row['username']; ?></td>
                            <td class="text-xs"><?php echo $row['email']; ?></td>
                            <td>
                                <div class="badge <?php echo $row['role'] == 'admin' ? 'badge-primary' : 'badge-ghost'; ?> text-[10px] uppercase font-bold">
                                    <?php echo $row['role']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="badge <?php echo $row['status_akun'] == 'aktif' ? 'badge-success' : 'badge-error'; ?> text-[10px] uppercase font-bold">
                                    <?php echo $row['status_akun']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <button class="btn btn-xs btn-info" onclick="editUser(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <?php if($row['id_user'] != $_SESSION['user_id']): ?>
                                    <a href="../process/user_process.php?action=toggle_status&id=<?php echo $row['id_user']; ?>" 
                                       class="btn btn-xs <?php echo $row['status_akun'] == 'aktif' ? 'btn-warning' : 'btn-success'; ?>"
                                       onclick="return confirm('<?php echo $row['status_akun'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan'; ?> user ini?')">
                                        <?php echo $row['status_akun'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan'; ?>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-20">
                                <i class="fa-solid fa-user-slash text-5xl opacity-10 mb-4 block"></i>
                                <p class="opacity-50 italic">Tidak ada user yang ditemukan.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<dialog id="add_user_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Tambah User Baru</h3>
        <form action="../process/user_process.php" method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-control">
                <label class="label"><span class="label-text">Nama Lengkap</span></label>
                <input type="text" name="nama_lengkap" class="input input-bordered" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Username</span></label>
                <input type="text" name="username" class="input input-bordered" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Email</span></label>
                <input type="email" name="email" class="input input-bordered" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Role</span></label>
                <select name="role" class="select select-bordered">
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Password</span></label>
                <input type="password" name="password" class="input input-bordered" required>
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="add_user_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Edit User Modal -->
<dialog id="edit_user_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Edit User</h3>
        <form action="../process/user_process.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_user" id="edit_user_id">
            <div class="form-control">
                <label class="label"><span class="label-text">Nama Lengkap</span></label>
                <input type="text" name="nama_lengkap" id="edit_user_nama" class="input input-bordered" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Role</span></label>
                <select name="role" id="edit_user_role" class="select select-bordered">
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text text-xs italic">Kosongkan jika tidak ingin ganti password</span></label>
                <input type="password" name="password" placeholder="Password Baru" class="input input-bordered">
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="edit_user_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</dialog>

<script>
function editUser(data) {
    document.getElementById('edit_user_id').value = data.id_user;
    document.getElementById('edit_user_nama').value = data.nama_lengkap;
    document.getElementById('edit_user_role').value = data.role;
    edit_user_modal.showModal();
}
</script>

<?php require_once '../includes/sidebar.php'; ?>
