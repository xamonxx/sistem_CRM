-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: crm_database
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `data_customer`
--

DROP TABLE IF EXISTS `data_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_customer` (
  `id_customer` int NOT NULL AUTO_INCREMENT,
  `nama_customer` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` text,
  `sumber_customer` enum('Email','Media Sosial','Telepon') DEFAULT 'Email',
  `status_customer` enum('Prospek','Aktif','Tidak Aktif') DEFAULT 'Prospek',
  `catatan` text,
  `tanggal_daftar` date DEFAULT (curdate()),
  `created_by` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_customer`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `data_customer_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_customer`
--

LOCK TABLES `data_customer` WRITE;
/*!40000 ALTER TABLE `data_customer` DISABLE KEYS */;
INSERT INTO `data_customer` VALUES (41,'Budi Santoso','budi@email.com','081234567890','Jakarta','Email','Aktif','Customer langganan','2026-01-12',1,NULL,'2026-01-12 01:55:20'),(42,'Siti Maria','siti@email.com','081222333444','Bandung','Media Sosial','Prospek','Tertarik paket promo','2026-01-12',1,NULL,'2026-01-12 01:55:20'),(43,'Andi Wijaya','andi@gmail.com','085566778899','Surabaya','Telepon','Aktif','Pelanggan baru','2026-01-12',1,NULL,'2026-01-12 01:55:20'),(44,'Dewi Lestari','dewi@yahoo.com','081122334455','Jogja','Email','Tidak Aktif','Sudah tidak berlangganan','2026-01-12',1,NULL,'2026-01-12 01:55:20'),(45,'Rian Hidayat','rian@outlook.com','089988776655','Semarang','Media Sosial','Prospek','Minta dikirim katalog','2026-01-12',1,NULL,'2026-01-12 01:55:20'),(46,'Ahmad Fauzi','ahmad.fauzi@gmail.com','081234567890','Jakarta Selatan','Email','Aktif','Pelanggan tetap sejak 2024','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(47,'Putri Handayani','putri.handayani@yahoo.com','082145678901','Bandung','Media Sosial','Prospek','Tertarik dengan paket premium','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(48,'Rizky Pratama','rizky.pratama@outlook.com','083256789012','Surabaya','Telepon','Aktif','Customer VIP','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(49,'Dian Sari','dian.sari@gmail.com','084367890123','Yogyakarta','Email','Tidak Aktif','Sudah tidak aktif 6 bulan','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(50,'Bambang Setiawan','bambang.s@company.co.id','085478901234','Semarang','Media Sosial','Aktif','Rekomendasi dari teman','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(51,'Rina Wulandari','rina.wulandari@gmail.com','086589012345','Malang','Email','Prospek','Butuh follow up minggu depan','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(52,'Hendra Gunawan','hendra.g@yahoo.com','087690123456','Medan','Telepon','Aktif','Pelanggan loyal','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(53,'Siska Amelia','siska.amelia@outlook.com','088701234567','Makassar','Media Sosial','Tidak Aktif','Pindah ke kompetitor','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(54,'Joko Widodo','joko.widodo@gmail.com','089812345678','Palembang','Email','Aktif','Langganan paket bisnis','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(55,'Maya Kusuma','maya.kusuma@yahoo.com','081923456789','Denpasar','Telepon','Prospek','Meeting dijadwalkan Senin','2026-01-12',1,'2026-01-11 20:58:45','2026-01-12 03:55:33'),(56,'Teguh Santosa','teguh.santosa@company.co.id','082034567890','Balikpapan','Email','Aktif','Customer enterprise','2026-01-12',1,'2026-01-11 21:36:42','2026-01-12 03:55:33'),(57,'Anita Rahayu','anita.rahayu@gmail.com','083145678901','Pontianak','Media Sosial','Aktif','Upgrade dari paket basic','2026-01-12',1,NULL,'2026-01-12 03:55:33'),(58,'Budi Hermawan','budi.hermawan@outlook.com','084256789012','Manado','Telepon','Tidak Aktif','Kontrak expired','2026-01-12',1,NULL,'2026-01-12 03:55:33'),(59,'Lia Fitriani','lia.fitriani@yahoo.com','085367890123','Banjarmasin','Email','Prospek','Tertarik demo produk','2026-01-12',1,NULL,'2026-01-12 03:55:33'),(60,'Eko Prasetyo','eko.prasetyo@gmail.com','086478901234','Batam','Media Sosial','Aktif','Referral dari Ahmad','2026-01-12',1,NULL,'2026-01-12 03:55:33'),(61,'Dewi Permata','dewi.permata@company.co.id','087589012345','Pekanbaru','Email','Aktif','Pelanggan premium 2 tahun','2026-01-12',1,'2026-01-11 21:01:10','2026-01-12 03:55:33'),(62,'Agus Suryadi','agus.suryadi@outlook.com','088690123456','Jambi','Telepon','Prospek','Butuh penawaran khusus','2026-01-12',1,'2026-01-11 21:01:10','2026-01-12 03:55:33'),(63,'Nina Maharani','nina.maharani@gmail.com','089701234567','Padang','Media Sosial','Tidak Aktif','Tidak merespons sejak Oktober','2026-01-12',1,'2026-01-11 21:01:10','2026-01-12 03:55:33'),(64,'Rudi Hartono','rudi.hartono@yahoo.com','081812345678','Lampung','Email','Aktif','Customer sejak founding','2026-01-12',1,'2026-01-11 21:01:10','2026-01-12 03:55:33'),(65,'Yanti Susilowati','yanti.susilowati@company.co.id','082923456789','Cirebon','Telepon','Prospek','Menunggu approval budget','2026-01-12',1,'2026-01-11 21:01:10','2026-01-12 03:55:33'),(66,'Ahmad Fauzi','ahmad.fauzi@gmail.com','081234567890','Jakarta Selatan','Email','Aktif','Pelanggan tetap sejak 2024','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(67,'Putri Handayani','putri.handayani@yahoo.com','082145678901','Bandung','Media Sosial','Prospek','Tertarik dengan paket premium','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(68,'Rizky Pratama','rizky.pratama@outlook.com','083256789012','Surabaya','Telepon','Aktif','Customer VIP','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(69,'Dian Sari','dian.sari@gmail.com','084367890123','Yogyakarta','Email','Tidak Aktif','Sudah tidak aktif 6 bulan','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(70,'Bambang Setiawan','bambang.s@company.co.id','085478901234','Semarang','Media Sosial','Aktif','Rekomendasi dari teman','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(71,'Rina Wulandari','rina.wulandari@gmail.com','086589012345','Malang','Email','Prospek','Butuh follow up minggu depan','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(72,'Hendra Gunawan','hendra.g@yahoo.com','087690123456','Medan','Telepon','Aktif','Pelanggan loyal','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(73,'Siska Amelia','siska.amelia@outlook.com','088701234567','Makassar','Media Sosial','Tidak Aktif','Pindah ke kompetitor','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(74,'Joko Widodo','joko.widodo@gmail.com','089812345678','Palembang','Email','Aktif','Langganan paket bisnis','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(75,'Maya Kusuma','maya.kusuma@yahoo.com','081923456789','Denpasar','Telepon','Prospek','Meeting dijadwalkan Senin','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(76,'Teguh Santosa','teguh.santosa@company.co.id','082034567890','Balikpapan','Email','Aktif','Customer enterprise','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(77,'Anita Rahayu','anita.rahayu@gmail.com','083145678901','Pontianak','Media Sosial','Aktif','Upgrade dari paket basic','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(78,'Budi Hermawan','budi.hermawan@outlook.com','084256789012','Manado','Telepon','Tidak Aktif','Kontrak expired','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(79,'Lia Fitriani','lia.fitriani@yahoo.com','085367890123','Banjarmasin','Email','Prospek','Tertarik demo produk','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(80,'Eko Prasetyo','eko.prasetyo@gmail.com','086478901234','Batam','Media Sosial','Aktif','Referral dari Ahmad','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(81,'Dewi Permata','dewi.permata@company.co.id','087589012345','Pekanbaru','Email','Aktif','Pelanggan premium 2 tahun','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(82,'Agus Suryadi','agus.suryadi@outlook.com','088690123456','Jambi','Telepon','Prospek','Butuh penawaran khusus','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(83,'Nina Maharani','nina.maharani@gmail.com','089701234567','Padang','Media Sosial','Tidak Aktif','Tidak merespons sejak Oktober','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(84,'Rudi Hartono','rudi.hartono@yahoo.com','081812345678','Lampung','Email','Aktif','Customer sejak founding','2026-01-12',1,NULL,'2026-01-12 06:12:16'),(85,'Yanti Susilowati','yanti.susilowati@company.co.id','082923456789','Cirebon','Telepon','Prospek','Menunggu approval budget','2026-01-12',1,NULL,'2026-01-12 06:12:16');
/*!40000 ALTER TABLE `data_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow_up`
--

DROP TABLE IF EXISTS `follow_up`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follow_up` (
  `id_followup` int NOT NULL AUTO_INCREMENT,
  `id_customer` int DEFAULT NULL,
  `tanggal_followup` date NOT NULL,
  `catatan` text,
  `status_followup` enum('Terjadwal','Selesai','Dibatalkan') DEFAULT 'Terjadwal',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_followup`),
  KEY `id_customer` (`id_customer`),
  CONSTRAINT `follow_up_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `data_customer` (`id_customer`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow_up`
--

LOCK TABLES `follow_up` WRITE;
/*!40000 ALTER TABLE `follow_up` DISABLE KEYS */;
INSERT INTO `follow_up` VALUES (6,59,'2026-01-12','hosadhfoihsdohsd','Terjadwal','2026-01-12 03:56:02'),(7,59,'2026-01-12','slkhflkasdhlkasdf','Selesai','2026-01-12 03:56:10'),(8,66,'2026-01-12','kshdlhlaksjld','Terjadwal','2026-01-12 06:48:48');
/*!40000 ALTER TABLE `follow_up` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_aktivitas`
--

DROP TABLE IF EXISTS `log_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_aktivitas` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `aktivitas` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_aktivitas`
--

LOCK TABLES `log_aktivitas` WRITE;
/*!40000 ALTER TABLE `log_aktivitas` DISABLE KEYS */;
INSERT INTO `log_aktivitas` VALUES (1,2,'User login','2026-01-09 19:07:15'),(2,2,'Menambah customer: bala bala','2026-01-09 19:24:44'),(3,2,'Menambah follow up untuk customer ID: 1','2026-01-09 19:25:39'),(4,2,'Mengedit customer: bala bala','2026-01-09 19:28:04'),(5,2,'Menambah follow up untuk customer ID: 1','2026-01-09 19:28:40'),(6,2,'User logout','2026-01-09 19:29:09'),(7,2,'User login','2026-01-09 19:30:41'),(8,2,'Menghapus customer (soft delete) ID: 2','2026-01-09 19:33:29'),(9,2,'Restore customer ID: 2','2026-01-09 19:33:36'),(10,2,'Menghapus customer (soft delete) ID: 2','2026-01-09 19:34:43'),(11,2,'Menghapus customer (soft delete) ID: 8','2026-01-09 19:34:48'),(12,2,'Bulk delete customer: 7 data','2026-01-09 19:37:06'),(13,2,'User logout','2026-01-09 19:46:08'),(14,6,'User login','2026-01-09 19:52:28'),(15,6,'Mengubah status user adminn menjadi nonaktif','2026-01-09 19:54:58'),(16,6,'Mengubah status user ramon menjadi nonaktif','2026-01-09 19:55:02'),(17,6,'User logout','2026-01-09 19:55:07'),(18,6,'User login','2026-01-09 19:55:26'),(19,6,'Import customer via CSV: Berhasil 5, Gagal 0','2026-01-09 20:07:18'),(20,6,'User login','2026-01-10 09:58:57'),(21,6,'Menambah customer: bundi','2026-01-10 10:00:12'),(22,6,'Restore customer ID: 4','2026-01-10 10:01:14'),(23,6,'User login','2026-01-10 10:06:55'),(24,6,'Menambah follow up untuk customer ID: 27','2026-01-10 10:13:09'),(25,6,'Menambah follow up untuk customer ID: 27','2026-01-10 10:13:31'),(26,6,'Admin memperbarui data user ID: 1','2026-01-10 10:17:35'),(27,6,'User logout','2026-01-10 10:17:41'),(28,1,'User login','2026-01-10 10:17:51'),(29,1,'User login','2026-01-10 10:43:41'),(30,1,'Menghapus customer (soft delete) ID: 27','2026-01-10 10:43:59'),(31,1,'Restore customer ID: 27','2026-01-10 10:44:06'),(32,1,'User logout','2026-01-10 10:44:18'),(33,1,'User login','2026-01-10 10:44:32'),(34,1,'Mengubah status user ramon menjadi aktif','2026-01-10 10:44:38'),(35,1,'User logout','2026-01-10 10:44:41'),(36,2,'User login','2026-01-10 10:44:43'),(37,2,'Import customer via CSV: Berhasil 5, Gagal 0','2026-01-10 10:48:00'),(38,8,'User login','2026-01-10 10:53:17'),(39,2,'User logout','2026-01-10 10:54:23'),(40,1,'User login','2026-01-10 10:54:59'),(41,1,'Admin memperbarui data user ID: 8','2026-01-10 10:55:31'),(42,8,'User logout','2026-01-10 10:55:47'),(43,8,'User login','2026-01-10 10:56:08'),(44,1,'User login','2026-01-10 15:36:03'),(45,1,'Menambah customer: revi santoso ','2026-01-10 15:36:50'),(46,1,'Menambah follow up untuk customer ID: 33','2026-01-10 15:37:23'),(47,1,'Admin memperbarui data user ID: 2','2026-01-10 15:38:30'),(48,1,'User logout','2026-01-10 15:38:34'),(49,2,'User login','2026-01-10 15:38:39'),(50,2,'Import customer via CSV: Berhasil 5, Gagal 0','2026-01-10 15:39:05'),(51,2,'Menghapus customer (soft delete) ID: 34','2026-01-10 15:39:15'),(52,2,'Restore customer ID: 34','2026-01-10 15:39:23'),(53,2,'Restore customer ID: 9','2026-01-10 15:39:30'),(54,9,'User login','2026-01-10 16:14:51'),(55,2,'Menambah customer: e9uteurt9uer','2026-01-10 16:15:36'),(56,2,'User logout','2026-01-10 16:15:51'),(57,1,'User login','2026-01-10 16:15:58'),(58,1,'Admin memperbarui data user ID: 9','2026-01-10 16:16:38'),(59,9,'User logout','2026-01-10 16:16:58'),(60,1,'Admin memperbarui data user ID: 9','2026-01-10 16:17:22'),(61,9,'User login','2026-01-10 16:18:05'),(62,1,'Mengubah status user raevv menjadi nonaktif','2026-01-10 16:18:12'),(63,9,'User logout','2026-01-10 16:18:16'),(64,1,'Mengubah status user raevv menjadi aktif','2026-01-10 16:18:17'),(65,9,'User login','2026-01-10 16:18:27'),(66,9,'User logout','2026-01-10 16:18:38'),(67,1,'Mengubah status user raevv menjadi nonaktif','2026-01-10 16:18:49'),(68,1,'Mengubah status user raevv menjadi aktif','2026-01-10 16:18:57'),(69,9,'User login','2026-01-10 16:19:00'),(70,1,'User login','2026-01-11 15:36:46'),(71,1,'Mengubah status user raevv menjadi nonaktif','2026-01-11 15:37:12'),(72,1,'Admin memperbarui data user ID: 9','2026-01-11 15:37:22'),(73,1,'Admin memperbarui data user ID: 9','2026-01-11 15:37:30'),(74,1,'Menambah customer: ibu tuti','2026-01-11 15:45:15'),(75,1,'Bulk delete customer: 20 data','2026-01-11 15:49:45'),(76,1,'Restore customer ID: 6','2026-01-11 15:50:02'),(77,1,'User login','2026-01-12 01:44:42'),(78,1,'Import customer via CSV: Berhasil 5, Gagal 0','2026-01-12 01:55:20'),(79,1,'User logout','2026-01-12 02:10:54'),(80,1,'User login','2026-01-12 02:12:49'),(81,1,'User logout','2026-01-12 02:16:20'),(82,1,'User login','2026-01-12 02:16:41'),(83,1,'User logout','2026-01-12 03:19:49'),(84,1,'User login','2026-01-12 03:20:00'),(85,1,'User logout','2026-01-12 03:20:59'),(86,1,'User login','2026-01-12 03:22:59'),(87,1,'User logout','2026-01-12 03:39:59'),(88,1,'User login','2026-01-12 03:40:01'),(89,1,'Import customer via CSV: Berhasil 20, Gagal 0','2026-01-12 03:55:33'),(90,1,'Menambah follow up untuk customer ID: 59','2026-01-12 03:56:02'),(91,1,'Menambah follow up untuk customer ID: 59','2026-01-12 03:56:10'),(92,1,'Admin memperbarui data user ID: 8','2026-01-12 03:56:32'),(93,1,'Admin menambah user baru: dwn','2026-01-12 03:57:18'),(94,1,'Bulk delete customer: 20 data','2026-01-12 03:58:45'),(95,1,'Bulk delete customer: 20 data','2026-01-12 04:01:10'),(96,1,'Restore customer ID: 56','2026-01-12 04:01:48'),(97,1,'Restore customer ID: 57','2026-01-12 04:01:53'),(98,1,'Restore customer ID: 58','2026-01-12 04:02:40'),(99,1,'Restore customer ID: 59','2026-01-12 04:02:48'),(100,1,'Restore customer ID: 60','2026-01-12 04:03:28'),(101,1,'User login','2026-01-12 04:35:59'),(102,1,'Menghapus customer (soft delete) ID: 56','2026-01-12 04:36:42'),(103,1,'User login','2026-01-12 06:11:48'),(104,1,'Import customer via CSV: Berhasil 20, Gagal 0','2026-01-12 06:12:16'),(105,1,'User login','2026-01-12 06:46:31'),(106,1,'Menambah follow up untuk customer ID: 66','2026-01-12 06:48:48'),(107,1,'User logout','2026-01-12 06:56:05'),(108,1,'User login','2026-01-12 06:56:18'),(109,1,'User logout','2026-01-12 06:57:04');
/*!40000 ALTER TABLE `log_aktivitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `status_akun` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin','admin@crm.com','$2y$10$UqnTOinKdjIHigL7kopR9.XzCk6LxQOMzALNFshApBo4K/u79DnNy','admin','aktif','2026-01-09 19:05:50'),(2,'ramadan','ramon','reymon031@gmail.com','$2y$10$brUrSxiUahlSb5aO1LzJu.hpjVq6rVRzn7o6wBihl8HRhqiP4yNnW','staff','aktif','2026-01-09 19:07:13'),(5,'Administrator Utama','adminn','adminn@crm.com','adminn','admin','nonaktif','2026-01-09 19:48:04'),(6,'user','user','user@gmail.com','$2y$10$O/v5te8vY5s.gvoNoSp9IOepbHs8yPMZy3JMTvP0jpYnzJdo4WQJS','admin','aktif','2026-01-09 19:51:52'),(8,'asep','asep','ahhshsuwhh@gmail.com','$2y$10$TAAZtw9l1HCPZgDS3fGLNe/GbHhTKJHMYrle98dXPrF8apXQBJ1Ay','admin','aktif','2026-01-10 10:53:03'),(9,'rmon','raevv','hrevvvv@gmail.com','$2y$10$zPTT03ZZ7KY/t4A8fWNW5udwUrZVKEGeK50fNswBgdgRkejWwLbSO','admin','nonaktif','2026-01-10 16:14:40'),(10,'adwin','dwn','dwn@gmail.com','$2y$10$WJM84f0P/czC6FqOQr3Rkehn703qiMrAcRgKWwoTD5cDcC24qFe9.','staff','aktif','2026-01-12 03:57:18');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-12 16:16:49
