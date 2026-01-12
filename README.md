# 🚀 CRM Native System

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![DaisyUI](https://img.shields.io/badge/DaisyUI-5A0EF8?style=for-the-badge&logo=daisyui&logoColor=white)

**Sistem CRM Modern dengan Antarmuka yang Elegan dan Responsif**

[Demo](#demo) • [Fitur](#-fitur) • [Instalasi](#-instalasi) • [Penggunaan](#-penggunaan) • [Lisensi](#-lisensi)

</div>

---

## 📸 Preview

<div align="center">
  <img src="https://via.placeholder.com/800x400/1a1a2e/16213e?text=CRM+Native+Dashboard" alt="Dashboard Preview" width="100%">
</div>

---

## ✨ Fitur

### 🎨 **Tampilan Modern**
- 35+ Tema DaisyUI yang dapat dipilih
- Floating Theme Controller
- Animasi halus dan transisi yang smooth
- Fully responsive untuk desktop dan mobile

### 👥 **Manajemen Customer**
- CRUD Customer lengkap
- Import data dari CSV
- Filter dan pencarian real-time
- Bulk delete dengan konfirmasi

### 📊 **Dashboard Interaktif**
- Statistik customer real-time
- Chart dan grafik visual
- Overview aktivitas terbaru

### 🔐 **Sistem Keamanan**
- Login & Register dengan validasi
- Role-based access (Admin/User)
- Session management dengan auto-logout
- Activity logging

### 📅 **Follow Up System**
- Penjadwalan follow up customer
- Reminder dan notifikasi
- Tracking history komunikasi

### 🗑️ **Recycle Bin**
- Soft delete untuk data customer
- Restore data yang terhapus
- Permanent delete dengan konfirmasi

---

## 🛠️ Tech Stack

| Technology | Description |
|------------|-------------|
| **PHP 7.4+** | Backend server-side |
| **MySQL** | Database management |
| **Tailwind CSS v3** | Utility-first CSS framework |
| **DaisyUI v4** | Component library untuk Tailwind |
| **Font Awesome 6** | Icon library |
| **Chart.js** | Data visualization |

---

## 📦 Instalasi

### Prasyarat

Pastikan Anda sudah menginstall:
- ✅ PHP 7.4 atau lebih baru
- ✅ MySQL 5.7 atau lebih baru
- ✅ Node.js 16+ dan NPM
- ✅ Web server (Apache/Nginx) atau Laragon/XAMPP

### Langkah Instalasi

#### 1️⃣ Clone Repository

```bash
git clone https://github.com/xamonxx/sistem_CRM.git
cd sistem_CRM
```

#### 2️⃣ Install Dependencies

```bash
npm install
```

#### 3️⃣ Setup Database

Buat database baru di MySQL:

```sql
CREATE DATABASE crm_native;
```

Import file SQL:

```bash
mysql -u root -p crm_native < database.sql
```

Atau import melalui phpMyAdmin dengan mengupload file `database.sql`.

#### 4️⃣ Konfigurasi Database

Edit file `config/database.php`:

```php
<?php
$host = "localhost";
$username = "root";        // Sesuaikan dengan username MySQL Anda
$password = "";            // Sesuaikan dengan password MySQL Anda
$database = "crm_native";  // Nama database

$conn = mysqli_connect($host, $username, $password, $database);
?>
```

#### 5️⃣ Build CSS (Tailwind)

Development mode (watch):
```bash
npm run watch
```

Production build:
```bash
npm run build
```

#### 6️⃣ Jalankan Aplikasi

Akses melalui browser:
```
http://localhost/sistem_CRM
```

---

## 🔑 Default Login

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin` |

> ⚠️ **Penting**: Segera ganti password default setelah login pertama!

---

## 📁 Struktur Folder

```
sistem_CRM/
├── 📁 config/
│   └── database.php          # Konfigurasi database
├── 📁 dist/
│   └── output.css            # Compiled Tailwind CSS
├── 📁 includes/
│   ├── functions.php         # Helper functions
│   ├── header.php            # Header template
│   └── sidebar.php           # Sidebar navigation
├── 📁 pages/
│   ├── dashboard.php         # Dashboard utama
│   ├── customer_list.php     # Manajemen customer
│   ├── follow_up.php         # Follow up system
│   ├── archive.php           # Recycle bin
│   ├── user_management.php   # Manajemen user (Admin)
│   └── activity_log.php      # Log aktivitas (Admin)
├── 📁 process/
│   └── *.php                 # Backend processing files
├── 📁 src/
│   └── input.css             # Tailwind source CSS
├── 📄 index.php              # Login page
├── 📄 register.php           # Registration page
├── 📄 logout.php             # Logout handler
├── 📄 database.sql           # Database schema
├── 📄 tailwind.config.js     # Tailwind configuration
├── 📄 package.json           # NPM dependencies
└── 📄 .gitignore             # Git ignore rules
```

---

## 🎨 Tema Tersedia

Aplikasi ini mendukung **35 tema DaisyUI**:

| Light Themes | Dark Themes | Unique Themes |
|--------------|-------------|---------------|
| 🌞 Light | 🌙 Dark | 🤖 Cyberpunk |
| 🧁 Cupcake | 🎹 Synthwave | 🧛 Dracula |
| 🐝 Bumblebee | 🎃 Halloween | 🧪 Acid |
| 💎 Emerald | 🌲 Forest | 🦄 Fantasy |
| 🏢 Corporate | 🌃 Night | 📐 Wireframe |
| 📺 Retro | ☕ Coffee | ⬛ Black |
| 💖 Valentine | 💼 Business | 👑 Luxury |
| 🌷 Garden | 🔅 Dim | 🖨️ CMYK |
| 🌊 Aqua | 🌌 Abyss | 🍮 Caramellatte |
| 🎵 Lofi | 🏔️ Nord | 🧵 Silk |
| 🎨 Pastel | 🌅 Sunset | |
| 🍋 Lemonade | | |
| ❄️ Winter | | |
| 🍂 Autumn | | |

---

## 📜 NPM Scripts

| Command | Description |
|---------|-------------|
| `npm run watch` | Watch mode untuk development |
| `npm run build` | Build production CSS |

---

## 🤝 Contributing

Kontribusi selalu diterima! Silakan buat Pull Request atau buka Issue untuk diskusi.

1. Fork repository ini
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buka Pull Request

---

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

---

## 👨‍💻 Author

**Raih Safir Ramadan**

- GitHub: [@xamonxx](https://github.com/xamonxx)
- Email: raihsafirzramadan@gmail.com

---

<div align="center">

**⭐ Jika project ini membantu, berikan bintang di repository ini! ⭐**

Made with ❤️ and ☕

</div>
