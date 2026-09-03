-- Migration: Tambah kolom yang hilang di tbl_data_produk
-- Jalankan query ini di database eshop_ci4

ALTER TABLE `tbl_data_produk`
    ADD COLUMN `gallery_images` TEXT DEFAULT NULL AFTER `foto_produk`,
    ADD COLUMN `size_guide_image` VARCHAR(255) DEFAULT NULL AFTER `gallery_images`,
    ADD COLUMN `video_url` VARCHAR(500) DEFAULT NULL AFTER `size_guide_image`;
