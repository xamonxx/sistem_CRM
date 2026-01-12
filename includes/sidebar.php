</div> <!-- Penutup drawer-content yang dibuka di header.php -->
<div class="drawer-side z-20">
    <label for="my-drawer-2 " class="drawer-overlay"></label>
    <ul class="menu p-4 w-64 min-h-full bg-base-100 text-base-content border-r border-base-300">
        <!-- Sidebar content here -->
        <li class="mb-4 text-center">
            <h1 class="text-2xl font-bold text-primary hover:bg-transparent cursor-default">CRM NATIVE</h1>
        </li>
        <div class="divider py-2">MENU</div>
        <li class="mb-1">
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active bg-primary text-primary-content hover:bg-primary/90' : 'hover:bg-base-200'; ?>">
                <i class="fa-solid fa-gauge w-6"></i> Dashboard
            </a>
        </li>
        <li class="mb-1">
            <a href="customer_list.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'customer_list.php' ? 'active bg-primary text-primary-content hover:bg-primary/90' : 'hover:bg-base-200'; ?>">
                <i class="fa-solid fa-users w-6"></i> Data Customer
            </a>
        </li>
        <li class="mb-1">
            <a href="follow_up.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'follow_up.php' ? 'active bg-primary text-primary-content hover:bg-primary/90' : 'hover:bg-base-200'; ?>">
                <i class="fa-solid fa-calendar-check w-6"></i> Follow Up
            </a>
        </li>
        <li class="mb-1">
            <a href="archive.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'archive.php' ? 'active bg-primary text-primary-content hover:bg-primary/90' : 'hover:bg-base-200'; ?>">
                <i class="fa-solid fa-trash-can w-6"></i> Recycle Bin
            </a>
        </li>

        <?php if ($_SESSION['role'] == 'admin'): ?>
            <div class="divider gap-2">ADMIN</div>
            <li class="mb-1">
                <a href="user_management.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'user_management.php' ? 'active bg-primary text-primary-content hover:bg-primary/90' : 'hover:bg-base-200'; ?>">
                    <i class="fa-solid fa-user-gear w-6"></i> Manajemen User
                </a>
            </li>
            <li class="mb-1">
                <a href="activity_log.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'activity_log.php' ? 'active bg-primary text-primary-content hover:bg-primary/90' : 'hover:bg-base-200'; ?>">
                    <i class="fa-solid fa-history w-6"></i> Log Aktivitas
                </a>
            </li>
        <?php endif; ?>

        <div class="mt-auto divider"></div>
        <li>
            <div class="flex items-center gap-3 py-4">
                <div class="avatar">
                    <div class="w-10 rounded-full">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama_lengkap']); ?>" />
                    </div>
                </div>
                <div>
                    <p class="font-bold text-xs truncate w-32"><?php echo $_SESSION['nama_lengkap']; ?></p>
                    <p class="text-[10px] uppercase opacity-50"><?php echo $_SESSION['role']; ?></p>
                </div>
            </div>
        </li>
        <li><a href="../logout.php" class="text-error"><i class="fa-solid fa-power-off w-6"></i> Logout</a></li>

    </ul>
</div>
</div>

</body>

</html>