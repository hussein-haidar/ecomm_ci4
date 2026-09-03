-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/

-- Host: ::1
-- Generation Time: 2025-06-09 16:19:59
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: stok_toko

DROP TABLE IF EXISTS `tbl_data_bank`;
CREATE TABLE `tbl_data_bank` (
  `id_bank` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `a_n` varchar(100) NOT NULL,
  `no_rek` varchar(100) NOT NULL,
  `nama_bank` varchar(100) NOT NULL,
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_bank`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_bank` (`id_bank`, `sesi_user`, `a_n`, `no_rek`, `nama_bank`, `deleted_at`) VALUES
    ('1', 'Tantowi', 'Tantowi', '2147483647', 'BRI', '0'),
    ('2', 'Tantowi', 'Tantowi', '2147483647', 'BSI', '0'),
    ('3', 'Tantowi', 'Tantowi', '2147483647', 'Mandiri', '0'),
    ('4', 'Tantowi', 'Tantowi', '2147483647', 'BCA', '0'),
    ('5', 'Agus', 'Agus', '29093209', 'BSI', '0'),
    ('6', 'Agus', 'Agus', '29093209', 'BRI', '0'),
    ('7', 'Agus', 'Agus', '29093209', 'BCA', '0');

DROP TABLE IF EXISTS `tbl_data_ekspedisi`;
CREATE TABLE `tbl_data_ekspedisi` (
  `id_ekspedisi` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pelanggan` varchar(100) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `jenis_kurir` varchar(100) NOT NULL,
  `ongkir` varchar(100) NOT NULL,
  `estimasi_waktu` varchar(100) NOT NULL,
  PRIMARY KEY (`id_ekspedisi`)
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_data_keranjang`;
CREATE TABLE `tbl_data_keranjang` (
  `id_keranjang` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pelanggan` varchar(100) NOT NULL,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL,
  `alamat` text NOT NULL,
  `id_stok` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `ukuran_produk` varchar(100) NOT NULL,
  `jumlah_produk` int(11) NOT NULL,
  `satuan_produk` varchar(100) NOT NULL,
  `harga_produk` decimal(10,2) NOT NULL,
  `berat_produk` decimal(10,2) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status_keranjang` enum('Proses','Selesai') NOT NULL DEFAULT 'Proses',
  `waktu_ditambahkan` datetime NOT NULL,
  PRIMARY KEY (`id_keranjang`)
) ENGINE=InnoDB AUTO_INCREMENT=457 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_data_kurir`;
CREATE TABLE `tbl_data_kurir` (
  `id_kurir` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `jenis_kurir` varchar(100) NOT NULL,
  `ongkir` decimal(10,2) NOT NULL,
  `deleted_at` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_kurir`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_kurir` (`id_kurir`, `sesi_user`, `jenis_kurir`, `ongkir`, `deleted_at`) VALUES
    ('2', 'Tantowi', 'Maxim', '2000.00', '0'),
    ('3', 'Tantowi', 'Gosend', '2500.00', '0'),
    ('4', 'Tantowi', 'Kurir Internal', '2000.00', '0'),
    ('5', 'Tantowi', 'JNE', '2000.00', '0'),
    ('6', 'Tantowi', 'JNT', '2000.00', '0');

DROP TABLE IF EXISTS `tbl_data_pelanggan`;
CREATE TABLE `tbl_data_pelanggan` (
  `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `no_telpon` varchar(100) NOT NULL,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL,
  `alamat` text NOT NULL,
  `foto_pelanggan` varchar(255) NOT NULL,
  `level` enum('3') NOT NULL DEFAULT '3',
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_pelanggan` (`id_pelanggan`, `sesi_user`, `email`, `password`, `nama_pelanggan`, `jenis_kelamin`, `tanggal_lahir`, `no_telpon`, `longitude`, `latitude`, `alamat`, `foto_pelanggan`, `level`, `last_login`) VALUES
    ('6', 'Tantowi', 'ahmad@gmail.com', '1234', 'Ahmad W', 'L', '1990-06-05', '0898988787875', '109.63457107369324', '-6.917977346429876', 'Samborejo, Tirto, Kab.Pekalongan, Jateng', '1736002127_f547bbf163f1afaa63eb.jpg', '3', '2025-06-07 17:10:40'),
    ('9', 'Agus', 'widi@gmail.com', '1234', 'Lala Widi', 'P', '2025-04-22', '085228666119', '109.0443420270458', '-6.903364297356772', 'Salem, Kab.Brebes, Jateng', '1745334710_9ce1ebe128886befa009.jpg', '3', '2025-05-25 22:19:30');

DROP TABLE IF EXISTS `tbl_data_pembayaran`;
CREATE TABLE `tbl_data_pembayaran` (
  `id_bayar` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `jumlah_produk` int(11) NOT NULL,
  `satuan_produk` varchar(100) NOT NULL,
  `ukuran_produk` varchar(100) NOT NULL,
  `a_n` varchar(100) NOT NULL,
  `no_rek` varchar(100) NOT NULL,
  `bank_tujuan` varchar(100) NOT NULL,
  `status_bayar` enum('Dibatalkan','Belum Bayar','Dibayar') NOT NULL DEFAULT 'Belum Bayar',
  `alasan_batal` varchar(100) NOT NULL,
  `waktu_pembayaran` datetime NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL,
  `batas_waktu_bayar` datetime NOT NULL,
  `foto_bayar` varchar(255) NOT NULL,
  `id_beli` int(11) NOT NULL,
  PRIMARY KEY (`id_bayar`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_data_pembelian`;
CREATE TABLE `tbl_data_pembelian` (
  `id_beli` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `kode_beli` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `id_stok` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `ukuran_produk` varchar(100) NOT NULL,
  `jumlah_produk` int(11) NOT NULL,
  `satuan_produk` varchar(100) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `total_berat` decimal(10,2) NOT NULL,
  `status_bayar` enum('Dibatalkan','Belum Bayar','Dibayar') NOT NULL DEFAULT 'Belum Bayar',
  `waktu_pembelian` datetime NOT NULL,
  PRIMARY KEY (`id_beli`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_data_produk`;
CREATE TABLE `tbl_data_produk` (
  `id_produk` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `kode_produk` varchar(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `checked` tinyint(1) NOT NULL,
  `ukuran_produk` varchar(100) DEFAULT NULL,
  `berat_produk` decimal(10,2) NOT NULL,
  `satuan_berat` varchar(100) NOT NULL,
  `deskripsi_produk` text NOT NULL,
  `harga_produk` decimal(10,2) NOT NULL,
  `foto_produk` varchar(255) NOT NULL,
  `id_varian` int(11) DEFAULT NULL,
  `id_jenis` int(11) DEFAULT NULL,
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_produk` (`id_produk`, `sesi_user`, `kode_produk`, `nama_produk`, `checked`, `ukuran_produk`, `berat_produk`, `satuan_berat`, `deskripsi_produk`, `harga_produk`, `foto_produk`, `id_varian`, `id_jenis`, `deleted_at`) VALUES
    ('1', 'Tantowi', 'DBS-001', 'Dress Batik Wanita Motif Soka', '0', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Bahan : Katun Saten.  Motif : Soka. Proses : Printing Tradisional. Ready Size : S-M-L-XL-XXL. Warna : Pink-Ungu-Biru Turkis-Biru', '56000.00', '1720330947_9c567bd92b08288c4534.jpg', '4', '1', '0'),
    ('2', 'Tantowi', 'DBPS-001', 'Dress Batik Wanita Parseka Sogan', '0', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Zepper Premium Bahan : Katun Saten. Motif : Parseka Sogan. Proses : Printing Tradisional Ready Size : S-M-L-XL-XXL. Warna : Hijau-Ungu-Biru-Coklat', '79000.00', '1720331146_abb727c3a744727a4483.jpg', '1', '1', '0'),
    ('3', 'Tantowi', 'KBPL-001', 'Kemeja Batik Lengan Panjang Pria Motif Parang Lurik', '0', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Bahan : Katun Poly Grade A. Proses : Printing Tradisional. Size : M-L-XL-XXL. Motif : Paksi Keling', '80000.00', '1720331700_569eada741d8afdb9d24.jpg', '2', '2', '0'),
    ('4', 'Tantowi', 'KBPK-001', 'Kemeja Batik Lengan Panjang Pria Motif Paksi Keling', '0', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Bahan : Katun Poly Grade A. Proses : Printing Tradisional. Size : M-L-XL-XXL. Motif : Paksi Keling', '67000.00', '1720331991_265f70e7d86d43f11542.jpg', '12', '2', '0'),
    ('5', 'Tantowi', 'KHSL-001', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Sopal Lumbu', '0', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Bahan : Katun Satin. Proses : Printing Tradisional. Size : S-M-L-XL-XXL. Motif : Sopal Lumbu', '89000.00', '1720332282_6700bf7bad528e78e97c.jpg', '7', '3', '0'),
    ('6', 'Tantowi', 'KHW-001', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Waru', '0', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Bahan : Katun Satin. Proses : Printing Tradisional. Size : S-M-L-XL-XXL. Motif : Waru', '76000.00', '1720332433_335459ea2d1b5b1feb3f.jpg', '6', '3', '0'),
    ('7', 'Tantowi', 'GBW-001', 'Gamis Batik Wanita Motif Waru', '0', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Bahan : Katun Bianca. Proses : Printing Tradisional. Size : M-L-XL-XXL. Motif : Paksi Keling.', '76969.00', '1720332830_ad1bdf90aa51ab5bbdf4.jpg', '6', '4', '0'),
    ('8', 'Tantowi', 'GBPK-001', 'Gamis Batik Wanita Motif Paksi Keling', '1', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Bahan : Katun Bianca. Proses : Printing Tradisional. Size : M-L-XL-XXL. Motif : Paksi Keling.', '65050.00', '1720333013_aefa117a9b36e036d6b2.jpg', '12', '4', '0'),
    ('9', 'Tantowi', 'KHTD-001', 'Hem Batik Pria Motif Tapak Doro', '1', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Bahan : Primis. Proses :Cap. Motif : Tapak Doro.  Size: S-L -XL-XXL', '85050.00', '1720333395_710695a72ac98c7f456d.jpg', '13', '5', '0'),
    ('10', 'Tantowi', 'KHKT-001', 'Kemeja Batik Lengan Pendek Pria Motif Kawung Tumpang', '1', 'S-M-L-XL-XXL', '500.00', 'Gram', 'Bahan : Primis. Proses : Batik Printing Tradisional. Motif: Kawung. Warna : Biru. Size: S-M-L-XL-XXL', '90660.00', '1720334381_e273b466890bcb630919.jpg', '18', '5', '0'),
    ('11', 'Tantowi', 'CKG-002', 'Celana Kulot Batik Wanita Motif Gajah', '0', 'L-XL', '500.00', 'Gram', 'Celana kulot . Motif Gajah. Bahan rayon.  Size: L-XL', '85300.00', '1720334878_0a3c665f4f8749d4de2a.jpg', '10', '7', '0'),
    ('12', 'Tantowi', 'CKG-001', 'Celana Kulot Batik Pria Motif Gronding', '0', 'L-XL', '500.00', 'Gram', 'Celana Kulot. Motif Batik: Gronding. Size: L-XL', '91002.00', '1720334822_45abe5b9430bbd11b117.jpg', '17', '6', '0'),
    ('13', 'Tantowi', 'CKW-001', 'Celana Kulot Batik Pria Motif Wayang', '1', 'S-M-L-XL', '500.00', 'Gram', 'Celana  kulot. Motif: Wayang. Bahan: rayon. Size: L-XL', '67000.00', '1720335152_d5537a6b393a3822eb19.jpg', '16', '6', '0'),
    ('14', 'Tantowi', 'RBL-001', 'Rok Batik Wanita Motif Lilit', '0', 'S-M-L-XL', '500.00', 'Gram', 'Bahan : Katun Primis. Proses : Cap Hand Made Tradisional. Motif: LIlit. Size: S-M-L-XL', '56000.00', '1720335653_f4853a1deb4a124c3058.jpg', '15', '8', '0'),
    ('24', 'Agus', 'STRL-001', 'Snack Taro Rumput Laut', '1', 'Big', '100.00', 'Gram', 'Snack para petualang sejati tanpa henti', '6000.00', '1746446446_5b74a928b0ad2d792a3f.jpg', '23', '14', '0'),
    ('25', 'Agus', 'SPVB-001', 'Snack Piatozz Varian BBQ', '1', 'Large', '100.00', 'Gram', 'Snack kentang renyah dan gurih', '6000.00', '1746446991_c0bab2249c8ba4bf9281.jpg', '21', '10', '0');

DROP TABLE IF EXISTS `tbl_data_user`;
CREATE TABLE `tbl_data_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `tampilkan_varian` tinyint(1) NOT NULL,
  `sesi_user` varchar(100) NOT NULL,
  `nama_title` varchar(100) NOT NULL,
  `notelpon_user` varchar(100) NOT NULL,
  `jobdesk_user` varchar(100) NOT NULL,
  `level` varchar(100) NOT NULL,
  `foto_user` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_user` (`id_user`, `username`, `password`, `nama_lengkap`, `tampilkan_varian`, `sesi_user`, `nama_title`, `notelpon_user`, `jobdesk_user`, `level`, `foto_user`, `last_login`) VALUES
    ('4', 'tantowi123', '1234', 'Tantowi', '1', 'Tantowi', 'Pemilik', '092098492829', 'Pemilik Toko', '1', '1736913528_ce14a2c09b0527014d3e.png', '2025-06-09 16:19:27'),
    ('5', 'admin', '1234', 'Ahmad', '1', 'Tantowi', 'Administrator', '089849859894', 'Mengelola stok', '2', '1736913412_0dd2339b7b8eac8cd5df.png', '2025-06-09 16:03:54'),
    ('6', 'agus123', '1234', 'Agus', '1', 'Agus', 'Pemilik', '0989086063', 'Pemilik  Majo Makmur', '1', '1743604603_8bca7e7b2e617c578e41.jpg', '2025-05-26 13:41:45'),
    ('8', 'admin123', '1234', 'Tyo', '1', 'Agus', 'Administrator', '089849859894', 'Mengelola data stok produk', '2', '1746346851_7b09d583ead823d2eea4.png', '2025-05-25 18:50:54');

DROP TABLE IF EXISTS `tbl_jenis_produk`;
CREATE TABLE `tbl_jenis_produk` (
  `id_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `jenis_produk` varchar(100) NOT NULL,
  `deleted_at` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_jenis_produk` (`id_jenis`, `sesi_user`, `jenis_produk`, `deleted_at`) VALUES
    ('1', 'Tantowi', 'Dress Batik Wanita', '0'),
    ('2', 'Tantowi', 'Kemeja Batik Lengan Panjang Pria', '0'),
    ('3', 'Tantowi', 'Kemeja Hem Batik Anak Cowok', '0'),
    ('4', 'Tantowi', 'Gamis Batik Wanita', '0'),
    ('5', 'Tantowi', 'Kemeja Hem Batik Pria', '0'),
    ('6', 'Tantowi', 'Celana Kulot Batik Pria', '0'),
    ('7', 'Tantowi', 'Celana Kulot Batik Wanita', '0'),
    ('8', 'Tantowi', 'Rok Batik Wanita', '0'),
    ('10', 'Agus', 'Snack Piatozz', '0'),
    ('13', 'Agus', 'Snack Hattari', '0'),
    ('14', 'Agus', 'Snack Taro', '0');

DROP TABLE IF EXISTS `tbl_satuan_produk`;
CREATE TABLE `tbl_satuan_produk` (
  `id_satuan` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `satuan_produk` varchar(100) NOT NULL,
  `deleted_at` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_satuan_produk` (`id_satuan`, `sesi_user`, `satuan_produk`, `deleted_at`) VALUES
    ('1', 'Agus', 'Item', '0'),
    ('2', 'Agus', 'Box', '0'),
    ('3', 'Agus', 'Pack', '0'),
    ('4', 'Agus', 'Kg', '0'),
    ('5', 'Agus', 'Gram', '0'),
    ('6', 'Agus', 'Liter', '0'),
    ('7', 'Agus', 'Botol', '0'),
    ('8', 'Tantowi', 'Item', '1'),
    ('9', 'Tantowi', 'Kotak', '1'),
    ('10', 'Tantowi', 'Pack', '1'),
    ('11', 'Tantowi', 'Kg', '0'),
    ('12', 'Tantowi', 'Gram', '0'),
    ('13', 'Tantowi', 'Kwintal', '0'),
    ('14', 'Tantowi', 'Item', '0'),
    ('15', 'Tantowi', 'Box', '0'),
    ('16', 'Tantowi', 'Pack', '0');

DROP TABLE IF EXISTS `tbl_stok_produk`;
CREATE TABLE `tbl_stok_produk` (
  `id_stok` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `kode_stok` varchar(11) NOT NULL,
  `jumlah_stok_produk` int(11) NOT NULL,
  `satuan_produk` varchar(100) NOT NULL,
  `tanggal_masuk_produk` date NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `jenis_produk` varchar(100) NOT NULL,
  `harga_produk` decimal(10,2) NOT NULL,
  `ukuran_produk` varchar(100) NOT NULL,
  `berat_produk` decimal(10,2) NOT NULL,
  `satuan_berat` varchar(100) NOT NULL,
  `total_berat` decimal(10,2) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id_stok`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_stok_produk` (`id_stok`, `sesi_user`, `kode_stok`, `jumlah_stok_produk`, `satuan_produk`, `tanggal_masuk_produk`, `nama_produk`, `jenis_produk`, `harga_produk`, `ukuran_produk`, `berat_produk`, `satuan_berat`, `total_berat`, `total_harga`) VALUES
    ('51', 'Tantowi', 'SKB-002', '2', 'Item', '2025-02-03', 'Kemeja Batik Lengan Panjang Pria Motif Parang Lurik', 'Kemeja Batik Lengan Panjang Pria', '80000.00', 'S-M-L-XL-XXL', '500.00', 'Gram', '1000.00', '160000.00'),
    ('52', 'Tantowi', 'SGB-001', '2', 'Item', '2025-02-04', 'Gamis Batik Wanita Motif Waru', 'Gamis Batik Wanita', '76969.00', 'S-M-L-XL-XXL', '500.00', 'Gram', '1000.00', '153938.00'),
    ('53', 'Tantowi', 'SKB-003', '3', 'Item', '2025-02-03', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Waru', 'Kemeja Hem Batik Anak Cowok', '76000.00', 'S-M-L-XL-XXL', '500.00', 'Gram', '1500.00', '228000.00'),
    ('54', 'Tantowi', 'SKB-004', '2', 'Item', '2025-02-04', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Waru', 'Kemeja Hem Batik Anak Cowok', '76000.00', 'S-M-L-XL-XXL', '500.00', 'Gram', '1000.00', '152000.00'),
    ('55', 'Tantowi', 'SDB-001', '2', 'Item', '2025-02-06', 'Dress Batik Wanita Parseka Sogan', 'Dress Batik Wanita', '79000.00', 'S-M-L-XL-XXL', '500.00', 'Gram', '1000.00', '158000.00'),
    ('56', 'Tantowi', 'SHB-001', '2', 'Item', '2025-02-07', 'Hem Batik Pria Motif Tapak Doro', 'Kemeja Hem Batik Pria', '85050.00', 'S-M-L-XL-XXL', '500.00', 'Gram', '1000.00', '170100.00'),
    ('57', 'Tantowi', 'SRB-002', '3', 'Item', '2025-04-23', 'Rok Batik Wanita Motif Lilit', 'Rok Batik Wanita', '56000.00', 'S-M-L-XL', '500.00', 'Gram', '1500.00', '168000.00'),
    ('58', 'Tantowi', 'SCK-001', '2', 'Item', '2025-04-23', 'Celana Kulot Batik Pria Motif Wayang', 'Celana Kulot Batik Pria', '67000.00', 'S-M-L-XL', '500.00', 'Gram', '1000.00', '134000.00'),
    ('59', 'Tantowi', 'SKB-001', '2', 'Item', '2025-04-29', 'Kemeja Batik Lengan Pendek Pria Motif Kawung Tumpang', 'Kemeja Hem Batik Pria', '90660.00', 'S-M-L-XL-XXL', '500.00', 'Gram', '1000.00', '181320.00'),
    ('63', 'Agus', 'SP-001', '15', 'Item', '2025-05-04', 'Snack Piatozz Varian BBQ', 'Snack Piatozz', '6000.00', 'Large', '100.00', 'Gram', '1500.00', '90000.00'),
    ('64', 'Agus', 'ST-001', '12', 'Item', '2025-05-05', 'Snack Taro Rumput Laut', 'Snack Taro', '6000.00', 'Big', '100.00', 'Gram', '1200.00', '72000.00'),
    ('65', 'Tantowi', 'SRB-001', '2', 'Item', '2025-05-15', 'Rok Batik Wanita Motif Lilit', 'Rok Batik Wanita', '56000.00', 'S-M-L-XL', '500.00', 'Gram', '1000.00', '112000.00');

DROP TABLE IF EXISTS `tbl_varian_produk`;
CREATE TABLE `tbl_varian_produk` (
  `id_varian` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `varian_produk` varchar(100) NOT NULL,
  `deleted_at` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_varian`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_varian_produk` (`id_varian`, `sesi_user`, `varian_produk`, `deleted_at`) VALUES
    ('1', 'Tantowi', 'Parseka Sogan', '0'),
    ('2', 'Tantowi', 'Parang Lurik', '0'),
    ('3', 'Tantowi', 'Cap Polosan', '0'),
    ('4', 'Tantowi', 'Soka', '0'),
    ('5', 'Tantowi', 'Panca Warna', '0'),
    ('6', 'Tantowi', 'Waru', '0'),
    ('7', 'Tantowi', 'Sopal Lumbu', '0'),
    ('8', 'Tantowi', 'Wayang', '0'),
    ('9', 'Tantowi', 'Gronding', '0'),
    ('10', 'Tantowi', 'Gajah', '0'),
    ('11', 'Tantowi', 'Songket', '0'),
    ('12', 'Tantowi', 'Paksi Keling', '0'),
    ('13', 'Tantowi', 'Tapak Doro', '0'),
    ('14', 'Tantowi', 'Kawung', '0'),
    ('15', 'Tantowi', 'Lilit', '0'),
    ('16', 'Tantowi', 'Wayang', '0'),
    ('17', 'Tantowi', 'Gronding', '0'),
    ('18', 'Tantowi', 'Kawung Tumpang', '0'),
    ('21', 'Agus', 'Varian BBQ', '0'),
    ('22', 'Agus', 'Sapi Panggang', '0'),
    ('23', 'Agus', 'Rumput Laut', '0');

DROP TABLE IF EXISTS `tbl_website`;
CREATE TABLE `tbl_website` (
  `id_website` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `level` varchar(100) NOT NULL,
  `nama_toko` varchar(100) NOT NULL,
  `latitude_pusat` double NOT NULL,
  `longitude_pusat` double NOT NULL,
  `alamat_pusat` text NOT NULL,
  `longitude_cabang` double NOT NULL,
  `latitude_cabang` double NOT NULL,
  `alamat_cabang` text NOT NULL,
  `wa_pusat` varchar(100) NOT NULL,
  `wa_cabang` varchar(100) NOT NULL,
  `logo_website` varchar(255) NOT NULL,
  `bgd_web` varchar(255) NOT NULL,
  `footer_title` text NOT NULL,
  `link_IG` varchar(100) NOT NULL,
  `link_FB` varchar(100) NOT NULL,
  `link_Tiktok` varchar(100) NOT NULL,
  `is_checked` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_website`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_website` (`id_website`, `sesi_user`, `level`, `nama_toko`, `latitude_pusat`, `longitude_pusat`, `alamat_pusat`, `longitude_cabang`, `latitude_cabang`, `alamat_cabang`, `wa_pusat`, `wa_cabang`, `logo_website`, `bgd_web`, `footer_title`, `link_IG`, `link_FB`, `link_Tiktok`, `is_checked`, `updated_at`) VALUES
    ('9', 'Tantowi', '1', 'BATIK FAARO ', '-6.936335068901984', '109.65632128781316', 'Buaran Gg.1 No.20, Buaran, Kota Pekalongan', '109.69182856716799', '-6.897406575571941', 'Pasar Grosir Setono Pekalongan, Kota Pekalongan', '08876776765765', '088786767868', '1743581398_eb6fea837fb492394c63.png', '1743581398_9fe79c143d92abdc3121.jpeg', 'Batik Faaro adalah toko online yang menyediakan berbagai pilihan produk batik berkualitas dengan desain eksklusif dan harga terjangkau. Kami berkomitmen memberikan pengalaman belanja yang aman, mudah, dan terpercaya bagi seluruh pelanggan kami.', 'https://instagram.com/namaakun', 'https://facebook.com/namaakun', 'https://tiktok.com/@namaakun', '2', '2025-06-06 10:21:09'),
    ('10', 'Agus', '1', 'MAJO MAKMUR', '-6.895960008743557', '109.6361884995486', 'Tirto, Tirto, Kab.Pekalongan', '109.691242065505', '-6.897730771029198', 'Grosir Setono, Gamer, Pekalongan', '+62898350839', '+62850957096', '1746674598_6d182ffed3caae2d6388.jpg', '1746674598_f4721637e96a5b0f4e85.jpg', 'Majo Makmur adalah toko terpercaya yang menyediakan beragam pilihan produk batik berkualitas, mengutamakan keaslian, kenyamanan, dan keindahan desain.          Berdiri dengan semangat melestarikan budaya lokal, kami hadir untuk memenuhi kebutuhan fashion batik modern dengan harga terjangkau dan pelayanan terbaik.', 'https://instagram.com/namaakun', 'https://facebook.com/namaakun', 'https://tiktok.com/namaakun', '2', '2025-04-04 19:05:26');

COMMIT;
