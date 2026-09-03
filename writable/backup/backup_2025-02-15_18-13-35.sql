-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/

-- Host: ::1
-- Generation Time: 2025-02-15 18:13:35
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
  `nama` varchar(100) NOT NULL,
  `no_rek` int(11) NOT NULL,
  `nama_bank` varchar(100) NOT NULL,
  `deleted_at` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_bank`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_bank` (`id_bank`, `nama`, `no_rek`, `nama_bank`, `deleted_at`) VALUES
    ('1', 'Tantowi', '2147483647', 'BRI', '0'),
    ('2', 'Tantowi', '2147483647', 'BSI', '0'),
    ('3', 'Tantowi', '2147483647', 'Mandiri', '0'),
    ('4', 'Tantowi', '2147483647', 'BCA', '0');

DROP TABLE IF EXISTS `tbl_data_keranjang`;
CREATE TABLE `tbl_data_keranjang` (
  `id_keranjang` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pelanggan` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `jumlah_produk` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status_keranjang` enum('proses','selesai') NOT NULL DEFAULT 'proses',
  `tanggal_ditambahkan` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_keranjang`)
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_keranjang` (`id_keranjang`, `nama_pelanggan`, `alamat`, `nama_produk`, `jumlah_produk`, `total_harga`, `status_keranjang`, `tanggal_ditambahkan`) VALUES
    ('166', 'Ahmad W', 'Brebes', 'Gamis Batik Wanita Motif Waru', '1', '76969', 'proses', '2025-02-05 20:59:37'),
    ('167', 'Ahmad W', 'Brebes', 'Hem Batik Pria Motif Tapak Doro', '1', '85050', 'proses', '2025-02-06 11:27:07'),
    ('168', 'Lala Widi', 'Brebes', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Waru', '1', '76000', 'selesai', '2025-02-10 11:16:08');

DROP TABLE IF EXISTS `tbl_data_pelanggan`;
CREATE TABLE `tbl_data_pelanggan` (
  `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `no_telpon` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `foto_pelanggan` varchar(255) NOT NULL,
  `level` enum('3') NOT NULL DEFAULT '3',
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_pelanggan` (`id_pelanggan`, `email`, `password`, `nama_pelanggan`, `jenis_kelamin`, `tanggal_lahir`, `no_telpon`, `alamat`, `foto_pelanggan`, `level`, `last_login`) VALUES
    ('6', 'ahmad@gmail.com', '1234', 'Ahmad W', 'L', '1990-06-05', '898988787875', 'Brebes', '1736002127_f547bbf163f1afaa63eb.jpg', '3', '2025-02-12 11:51:18'),
    ('8', 'widi@gmail.com', '1234', 'Lala Widi', 'P', '2025-01-06', '0898988787878', 'Brebes', '1736177066_539b57e1d26635f74034.jpg', '3', '2025-02-10 18:55:36');

DROP TABLE IF EXISTS `tbl_data_pembelian`;
CREATE TABLE `tbl_data_pembelian` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `bank_asal` varchar(100) NOT NULL,
  `bank_tujuan` varchar(100) NOT NULL,
  `no_rek` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `jumlah_produk` int(11) NOT NULL,
  `total_berat` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `foto_bayar` varchar(255) NOT NULL,
  `status` enum('pending','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `provinsi` varchar(100) NOT NULL,
  `kota` varchar(100) NOT NULL,
  `ekspedisi` varchar(100) NOT NULL,
  `paket` varchar(100) NOT NULL,
  `ongkir` varchar(100) NOT NULL,
  `estimasi` varchar(100) NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_pembayaran` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_pembelian` (`id_transaksi`, `kode_transaksi`, `nama_pelanggan`, `alamat`, `bank_asal`, `bank_tujuan`, `no_rek`, `nama_produk`, `jumlah_produk`, `total_berat`, `total_bayar`, `foto_bayar`, `status`, `provinsi`, `kota`, `ekspedisi`, `paket`, `ongkir`, `estimasi`, `tanggal_transaksi`, `tanggal_pembayaran`) VALUES
    ('33', 'T20250205-001', 'Ahmad W', 'Brebes', 'BRI', 'BSI', '2147483647', 'Gamis Batik Wanita Motif Waru', '1', '500', '76969', '1738800258_f0f31a7bb2cd5a4d5e0a.jpeg', 'selesai', '9', '430', 'pos', 'Pos Reguler', 'Rp 48.523', '5-8 Hari', '2025-02-06 07:05:57', '2025-02-10 17:48:35'),
    ('36', 'T20250210-001', 'Lala Widi', 'Brebes', 'Mandiri', 'Mandiri', '2147483647', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Waru', '1', '500', '76000', '1739191916_7f0096b6ab73734d3bea.jpg', 'pending', '13', '33', 'tiki', 'ECO', 'Rp 15.136', '5-8 Hari', '2025-02-10 11:20:32', '2025-02-10 19:51:56');

DROP TABLE IF EXISTS `tbl_data_produk`;
CREATE TABLE `tbl_data_produk` (
  `id_produk` int(11) NOT NULL AUTO_INCREMENT,
  `kode_produk` varchar(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `berat_produk` int(11) NOT NULL,
  `deskripsi_produk` text NOT NULL,
  `harga_produk` int(100) NOT NULL,
  `foto_produk` varchar(255) NOT NULL,
  `id_motif` int(11) DEFAULT NULL,
  `id_jenis` int(11) DEFAULT NULL,
  `deleted_at` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_produk` (`id_produk`, `kode_produk`, `nama_produk`, `berat_produk`, `deskripsi_produk`, `harga_produk`, `foto_produk`, `id_motif`, `id_jenis`, `deleted_at`) VALUES
    ('1', 'DBS-001', 'Dress Batik Wanita Motif Soka', '500', 'Bahan : Katun Saten.  Motif : Soka. Proses : Printing Tradisional. Ready Size : S-M-L-XL-XXL. Warna : Pink-Ungu-Biru Turkis-Biru', '56000', '1720330947_9c567bd92b08288c4534.jpg', '4', '1', '0'),
    ('2', 'DBPS-001', 'Dress Batik Wanita Parseka Sogan', '500', 'Zepper Premium Bahan : Katun Saten. Motif : Parseka Sogan. Proses : Printing Tradisional Ready Size : S-M-L-XL-XXL. Warna : Hijau-Ungu-Biru-Coklat', '79000', '1720331146_abb727c3a744727a4483.jpg', '1', '1', '0'),
    ('3', 'KBPL-001', 'Kemeja Batik Lengan Panjang Pria Motif Parang Lurik', '500', 'Bahan : Katun Poly Grade A. Proses : Printing Tradisional. Size : M-L-XL-XXL. Motif : Paksi Keling', '80000', '1720331700_569eada741d8afdb9d24.jpg', '2', '2', '0'),
    ('4', 'KBPK-001', 'Kemeja Batik Lengan Panjang Pria Motif Paksi Keling', '500', 'Bahan : Katun Poly Grade A. Proses : Printing Tradisional. Size : M-L-XL-XXL. Motif : Paksi Keling', '67000', '1720331991_265f70e7d86d43f11542.jpg', '12', '2', '0'),
    ('5', 'KHSL-001', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Sopal Lumbu', '500', 'Bahan : Katun Satin. Proses : Printing Tradisional. Size : S-M-L-XL-XXL. Motif : Sopal Lumbu', '89000', '1720332282_6700bf7bad528e78e97c.jpg', '7', '3', '0'),
    ('6', 'KHW-001', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Waru', '500', 'Bahan : Katun Satin. Proses : Printing Tradisional. Size : S-M-L-XL-XXL. Motif : Waru', '76000', '1720332433_335459ea2d1b5b1feb3f.jpg', '6', '3', '0'),
    ('7', 'GBW-001', 'Gamis Batik Wanita Motif Waru', '500', 'Bahan : Katun Bianca. Proses : Printing Tradisional. Size : M-L-XL-XXL. Motif : Paksi Keling.', '76969', '1720332830_ad1bdf90aa51ab5bbdf4.jpg', '6', '4', '0'),
    ('8', 'GBPK-001', 'Gamis Batik Wanita Motif Paksi Keling', '500', 'Bahan : Katun Bianca. Proses : Printing Tradisional. Size : M-L-XL-XXL. Motif : Paksi Keling.', '65050', '1720333013_aefa117a9b36e036d6b2.jpg', '12', '4', '0'),
    ('9', 'KHTD-001', 'Hem Batik Pria Motif Tapak Doro', '500', 'Bahan : Primis. Proses :Cap. Motif : Tapak Doro.  Size: S-L -XL-XXL', '85050', '1720333395_710695a72ac98c7f456d.jpg', '13', '5', '0'),
    ('10', 'KHKT-001', 'Kemeja Batik Lengan Pendek Pria Motif Kawung Tumpang', '500', 'Bahan : Primis. Proses : Batik Printing Tradisional. Motif: Kawung. Warna : Biru. Size: S-M-L-XL-XXL', '90660', '1720334381_e273b466890bcb630919.jpg', '18', '5', '0'),
    ('11', 'CKG-002', 'Celana Kulot Batik Wanita Motif Gajah', '500', 'Celana kulot . Motif Gajah. Bahan rayon.  Size: L-XL', '85300', '1720334878_0a3c665f4f8749d4de2a.jpg', '10', '7', '0'),
    ('12', 'CKG-001', 'Celana Kulot Batik Pria Motif Gronding', '500', 'Celana Kulot. Motif Batik: Gronding. Size: L-XL', '91002', '1720334822_45abe5b9430bbd11b117.jpg', '17', '6', '0'),
    ('13', 'CKW-001', 'Celana Kulot Batik Pria Motif Wayang', '500', 'Celana  kulot. Motif: Wayang. Bahan: rayon. Size: L-XL', '93400', '1720335152_d5537a6b393a3822eb19.jpg', '16', '6', '0'),
    ('14', 'RBL-001', 'Rok Batik Wanita Motif Lilit', '500', 'Bahan : Katun Primis. Proses : Cap Hand Made Tradisional. Motif: LIlit. Size: S-M-L-XL', '76500', '1720335653_f4853a1deb4a124c3058.jpg', '15', '8', '0');

DROP TABLE IF EXISTS `tbl_data_user`;
CREATE TABLE `tbl_data_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nama_title` varchar(100) NOT NULL,
  `notelpon_user` varchar(100) NOT NULL,
  `jobdesk_user` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `foto_user` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_data_user` (`id_user`, `username`, `password`, `nama_lengkap`, `nama_title`, `notelpon_user`, `jobdesk_user`, `level`, `foto_user`, `last_login`) VALUES
    ('4', 'pemilik', '1234', 'Tantowi', 'Pemilik', '092098492829', 'Pemilik', '1', '1736913528_ce14a2c09b0527014d3e.png', '2025-02-15 18:13:29'),
    ('5', 'admin', '1234', 'Admin', 'Administrator', '089849859894', 'Mengelola stok', '2', '1736913412_0dd2339b7b8eac8cd5df.png', '2025-02-10 14:43:29');

DROP TABLE IF EXISTS `tbl_jenis_produk`;
CREATE TABLE `tbl_jenis_produk` (
  `id_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_produk` varchar(100) NOT NULL,
  `deleted_at` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_jenis_produk` (`id_jenis`, `jenis_produk`, `deleted_at`) VALUES
    ('1', 'Dress Batik Wanita', '0'),
    ('2', 'Kemeja Batik Lengan Panjang Pria', '0'),
    ('3', 'Kemeja Hem Batik Anak Cowok', '0'),
    ('4', 'Gamis Batik Wanita', '0'),
    ('5', 'Kemeja Hem Batik Pria', '0'),
    ('6', 'Celana Kulot Batik Pria', '0'),
    ('7', 'Celana Kulot Batik Wanita', '0'),
    ('8', 'Rok Batik Wanita', '0');

DROP TABLE IF EXISTS `tbl_motif_produk`;
CREATE TABLE `tbl_motif_produk` (
  `id_motif` int(11) NOT NULL AUTO_INCREMENT,
  `motif_produk` varchar(100) NOT NULL,
  `deleted_at` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id_motif`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_motif_produk` (`id_motif`, `motif_produk`, `deleted_at`) VALUES
    ('1', 'Parseka Sogan', '0'),
    ('2', 'Parang Lurik', '0'),
    ('3', 'Cap Polosan', '0'),
    ('4', 'Soka', '0'),
    ('5', 'Panca Warna', '0'),
    ('6', 'Waru', '0'),
    ('7', 'Sopal Lumbu', '0'),
    ('8', 'Wayang', '0'),
    ('9', 'Gronding', '0'),
    ('10', 'Gajah', '0'),
    ('11', 'Songket', '0'),
    ('12', 'Paksi Keling', '0'),
    ('13', 'Tapak Doro', '0'),
    ('14', 'Kawung', '0'),
    ('15', 'Lilit', '0'),
    ('16', 'Wayang', '0'),
    ('17', 'Gronding', '0'),
    ('18', 'Kawung Tumpang', '0');

DROP TABLE IF EXISTS `tbl_stok_produk`;
CREATE TABLE `tbl_stok_produk` (
  `id_stok` int(11) NOT NULL AUTO_INCREMENT,
  `kode_stok` varchar(11) NOT NULL,
  `jumlah_stok_produk` int(11) NOT NULL,
  `tanggal_masuk_produk` date NOT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `jenis_produk` varchar(100) NOT NULL,
  `kriteria_produk` enum('Pria','Wanita') NOT NULL,
  `harga_produk` int(11) NOT NULL,
  `berat_produk` int(11) NOT NULL,
  `total_berat` int(11) DEFAULT NULL,
  `total_harga` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_stok`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_stok_produk` (`id_stok`, `kode_stok`, `jumlah_stok_produk`, `tanggal_masuk_produk`, `nama_produk`, `jenis_produk`, `kriteria_produk`, `harga_produk`, `berat_produk`, `total_berat`, `total_harga`) VALUES
    ('51', 'KB-001', '0', '2025-02-03', 'Kemeja Batik Lengan Panjang Pria Motif Parang Lurik', '', 'Pria', '80000', '500', '0', '0'),
    ('52', 'GB-001', '0', '2025-02-04', 'Gamis Batik Wanita Motif Waru', '', 'Wanita', '76969', '500', '0', '0'),
    ('53', 'KB-002', '2', '2025-02-03', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Waru', '', 'Pria', '76000', '500', '1000', '152000'),
    ('54', 'KB-003', '0', '2025-02-04', 'Kemeja Batik Lengan Pendek Anak Cowok Motif Waru', '', 'Pria', '76000', '500', '0', '0'),
    ('55', 'DB-001', '1', '2025-02-06', 'Dress Batik Wanita Parseka Sogan', '', 'Wanita', '79000', '500', '500', '79000'),
    ('56', 'HB-001', '2', '2025-02-07', 'Hem Batik Pria Motif Tapak Doro', '', 'Pria', '85050', '500', '1000', '170100');

DROP TABLE IF EXISTS `tbl_website`;
CREATE TABLE `tbl_website` (
  `id_website` int(11) NOT NULL AUTO_INCREMENT,
  `nama_website` varchar(100) NOT NULL,
  `nama_toko` varchar(100) NOT NULL,
  `alamat_pusat` text NOT NULL,
  `alamat_cabang` text NOT NULL,
  `wa_pusat` varchar(100) NOT NULL,
  `wa_cabang` varchar(100) NOT NULL,
  `logo_website` varchar(255) NOT NULL,
  `bgd_web` varchar(255) NOT NULL,
  PRIMARY KEY (`id_website`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_website` (`id_website`, `nama_website`, `nama_toko`, `alamat_pusat`, `alamat_cabang`, `wa_pusat`, `wa_cabang`, `logo_website`, `bgd_web`) VALUES
    ('8', 'GUDANG WEB FAARO', 'BATIK FAARO', 'Buaran Gg.1 No.20, Buaran, Kota Pekalongan', 'Pasar Grosir Setono Pekalongan, Kota Pekalongan', '0862285428653', '0862285428974', '1733665762_41c7e5683e702a28c5a4.png', '1733665762_f79fd52ab447721d8c2a.jpeg');

COMMIT;
