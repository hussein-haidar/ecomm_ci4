-- =============================================================
-- Migrasi: Tabel Banner Promo (Slider Toko)
-- Jalankan sekali di database eshop_ci4
-- =============================================================
CREATE TABLE IF NOT EXISTS `tbl_promo_banner` (
  `id_banner` int(11) NOT NULL AUTO_INCREMENT,
  `sesi_user` varchar(100) NOT NULL,
  `judul_banner` varchar(100) NOT NULL,
  `deskripsi_banner` text DEFAULT NULL,
  `foto_banner` varchar(255) NOT NULL,
  `link_banner` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_banner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;