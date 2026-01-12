CREATE DATABASE IF NOT EXISTS crm_database;
USE crm_database;

CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    status_akun ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS data_customer (
    id_customer INT AUTO_INCREMENT PRIMARY KEY,
    nama_customer VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    no_telepon VARCHAR(20),
    alamat TEXT,
    sumber_customer ENUM('Email', 'Media Sosial', 'Telepon') DEFAULT 'Email',
    status_customer ENUM('Prospek', 'Aktif', 'Tidak Aktif') DEFAULT 'Prospek',
    catatan TEXT,
    tanggal_daftar DATE DEFAULT (CURRENT_DATE),
    created_by INT,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id_user)
);

CREATE TABLE IF NOT EXISTS log_aktivitas (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    aktivitas TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
);

CREATE TABLE IF NOT EXISTS follow_up (
    id_followup INT AUTO_INCREMENT PRIMARY KEY,
    id_customer INT,
    tanggal_followup DATE NOT NULL,
    catatan TEXT,
    status_followup ENUM('Terjadwal', 'Selesai', 'Dibatalkan') DEFAULT 'Terjadwal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_customer) REFERENCES data_customer(id_customer) ON DELETE CASCADE
);

-- Default User (Admin)
-- Password: admin123
INSERT INTO users (nama_lengkap, username, email, password, role, status_akun) 
VALUES ('Administrator', 'admin', 'admin@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'aktif');
