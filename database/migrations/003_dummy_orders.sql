-- ============================================================
-- CI3 Logistics Operation System - Dummy Orders Seeder (Improved)
-- Run this script to populate system with active & historical dummy data
-- ============================================================

USE `db_ci_apps`;

-- Delete existing test/dummy records to avoid duplication
DELETE FROM `pod_submissions` WHERE `order_id` BETWEEN 10 AND 30;
DELETE FROM `pre_trip_inspections` WHERE `order_id` BETWEEN 10 AND 30;
DELETE FROM `orders` WHERE `id` BETWEEN 10 AND 30;

-- Reset auto-increment safely
ALTER TABLE `orders` AUTO_INCREMENT = 10;

-- -----------------------------------------------------------
-- 1. Insert Dummy Orders representing different flow stages
-- -----------------------------------------------------------
INSERT INTO `orders` (
  `id`, `customer_name`, `cargo_description`, `weight`, `volume`, 
  `origin`, `origin_latitude`, `origin_longitude`, 
  `destination`, `destination_latitude`, `destination_longitude`, 
  `eta`, `status`, `driver_id`, `vehicle_id`, `uang_jalan`, 
  `seal_number`, `loading_notes`, `loading_verified_by`, `loading_verified_at`, `created_at`
) VALUES
-- Order 10: Pending (Waiting dispatch assignment)
(10, 'PT Indofood CBP Sukses Makmur Tbk', 'Indomie Goreng & Indomie Soto 1500 Dus', 9.00, 30.00, 'Jakarta (Sunter)', -6.13840000, 106.88360000, 'Semarang (KIC)', -6.96670000, 110.41670000, DATE_ADD(NOW(), INTERVAL 1 DAY), 'pending', NULL, NULL, 0.00, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 2 HOUR)),

-- Order 11: Allocated (Assigned driver & vehicle, waiting driver pre-trip check)
(11, 'PT Mayora Indah Tbk', 'Kopiko, Roma Kelapa & Beng-Beng 10 Pallet', 4.50, 16.00, 'Tangerang (Batuceper)', -6.17020000, 106.67190000, 'Cirebon (Weru)', -6.72080000, 108.55590000, DATE_ADD(NOW(), INTERVAL 18 HOUR), 'allocated', 10, 6, 750000.00, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 3 HOUR)),

-- Order 12: Ready (Pre-trip passed, ready to print Surat Jalan / depart)
(12, 'PT Unilever Indonesia Tbk', 'Sabun Lifebuoy & Pasta Gigi Pepsodent 12 Pallet', 7.20, 24.50, 'Bekasi (Cikarang)', -6.28560000, 107.17060000, 'Bandung (Gedebage)', -6.94580000, 107.68450000, DATE_ADD(NOW(), INTERVAL 12 HOUR), 'ready', 7, 2, 600000.00, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 5 HOUR)),

-- Order 13: Loading (Pre-trip passed, currently loading cargo, waiting Checker verification)
(13, 'PT Wings Surya', 'Deterjen Daia & So Klin Liquid 800 Dus', 6.00, 15.00, 'Jakarta (Cakung)', -6.19530000, 106.95110000, 'Bogor (Ciawi)', -6.65210000, 106.84890000, DATE_ADD(NOW(), INTERVAL 8 HOUR), 'loading', 8, 4, 450000.00, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 1.5 HOUR)),

-- Order 14: In Transit (En route to Surabaya, GPS tracking active)
(14, 'PT Nestle Indonesia', 'Susu Dancow & Kopi Nescafe 15 Ton', 15.00, 45.00, 'Karawang (KIIC)', -6.36010000, 107.28030000, 'Surabaya (Rungkut)', -7.32140000, 112.76840000, DATE_ADD(NOW(), INTERVAL 14 HOUR), 'in_transit', 5, 1, 2400000.00, 'SL-UN-9021-098', 'Kargo dimuat rapi, pintu boks disegel rapat.', 4, DATE_SUB(NOW(), INTERVAL 8 HOUR), DATE_SUB(NOW(), INTERVAL 10 HOUR)),

-- Order 15: Arrived (At unloading dock, waiting driver to upload POD)
(15, 'PT Kalbe Farma Tbk', 'Obat-obatan & Vitamin 5 Pallet', 3.50, 12.00, 'Bekasi (Cikarang)', -6.28560000, 107.17060000, 'Solo (Kartasura)', -7.55830000, 110.74230000, DATE_SUB(NOW(), INTERVAL 1 HOUR), 'arrived', 12, 8, 800000.00, 'SL-KF-9388-124', 'Muatan rapi, pintu boks terkunci.', 4, DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 6 HOUR)),

-- Order 16: POD Submitted (POD uploaded by driver, waiting Admin verification)
(16, 'PT Gudang Garam Tbk', 'Rokok Gudang Garam Filter 300 Karton', 6.50, 22.00, 'Kediri', -7.81720000, 112.01140000, 'Jakarta (Cakung)', -6.19530000, 106.95110000, DATE_SUB(NOW(), INTERVAL 2 HOUR), 'pod_submitted', 9, 5, 1800000.00, 'SL-GG-9132-756', 'Muatan rokok disegel rapat.', 4, DATE_SUB(NOW(), INTERVAL 17 HOUR), DATE_SUB(NOW(), INTERVAL 18 HOUR)),

-- Order 17: Inspect Failed (Pre-trip check failed, driver Maman blocked, truck in maintenance)
(17, 'PT Pertamina Lubricants', 'Oli Mesran & Enduro 80 Drum', 12.00, 28.00, 'Jakarta (Tanjung Priok)', -6.10340000, 106.88190000, 'Bandung (Soekarno Hatta)', -6.94120000, 107.62560000, DATE_ADD(NOW(), INTERVAL 1 DAY), 'inspect_failed', 11, 3, 500000.00, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 4 HOUR)),

-- Order 18: Completed (Historical delivery for driver Sutrisno)
(18, 'PT Sosro', 'Teh Botol Sosro 800 Krat', 6.00, 18.00, 'Jakarta', -6.20880000, 106.84560000, 'Bandung', -6.91750000, 107.61910000, DATE_SUB(NOW(), INTERVAL 1 DAY), 'completed', 5, 1, 650000.00, 'SL-SS-9021-002', 'Muatan teh botol krat rapi.', 4, DATE_SUB(NOW(), INTERVAL 22 HOUR), DATE_SUB(NOW(), INTERVAL 24 HOUR)),

-- Order 19: Completed (Historical delivery for driver Bambang)
(19, 'PT Charoen Pokphand Indonesia Tbk', 'Pakan Ayam 15 Ton', 15.00, 35.00, 'Tangerang', -6.17830000, 106.63190000, 'Sukabumi', -6.91810000, 106.92670000, DATE_SUB(NOW(), INTERVAL 2 DAY), 'completed', 7, 6, 550000.00, 'SL-CP-9977-111', 'Pakan ternak dalam karung aman.', 4, DATE_SUB(NOW(), INTERVAL 1.8 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),

-- Order 20: Completed (Historical delivery for driver Agus)
(20, 'PT Astra Honda Motor', 'Sepeda Motor Honda Beat 40 Unit', 4.00, 25.00, 'Karawang', -6.30240000, 107.30510000, 'Yogyakarta', -7.79560000, 110.36950000, DATE_SUB(NOW(), INTERVAL 3 DAY), 'completed', 8, 2, 1400000.00, 'SL-AHM-9812-322', 'Motor aman dengan pelindung busa.', 4, DATE_SUB(NOW(), INTERVAL 2.8 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),

-- Order 21: Completed (Historical delivery for driver Eko)
(21, 'PT Kimia Farma Tbk', 'Obat & Alkes 3 Pallet', 2.00, 8.00, 'Jakarta', -6.20880000, 106.84560000, 'Cirebon', -6.72080000, 108.55590000, DATE_SUB(NOW(), INTERVAL 4 DAY), 'completed', 9, 7, 700000.00, 'SL-KF-9422-044', 'Alkes steril aman dalam boks.', 4, DATE_SUB(NOW(), INTERVAL 3.8 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),

-- Order 22: Completed (Historical delivery for driver Dedi)
(22, 'PT HM Sampoerna Tbk', 'Rokok Sampoerna Mild 150 Karton', 3.50, 14.00, 'Surabaya', -7.25750000, 112.75210000, 'Solo', -7.57550000, 110.82430000, DATE_SUB(NOW(), INTERVAL 5 DAY), 'completed', 10, 5, 850000.00, 'SL-HMS-9132-099', 'Muatan rokok disegel kering.', 4, DATE_SUB(NOW(), INTERVAL 4.8 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),

-- Order 23: Completed (Historical delivery for driver Maman)
(23, 'PT Toyota Motor Manufacturing', 'Suku Cadang Mobil 8 Pallet', 6.00, 20.00, 'Karawang', -6.30240000, 107.30510000, 'Jakarta', -6.20880000, 106.84560000, DATE_SUB(NOW(), INTERVAL 6 DAY), 'completed', 11, 4, 400000.00, 'SL-TY-9211-102', 'Suku cadang besi terlindungi oli.', 4, DATE_SUB(NOW(), INTERVAL 5.8 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),

-- Order 24: Completed (Historical delivery for driver Budi Santoso)
(24, 'PT Japfa Comfeed Indonesia Tbk', 'Pakan Ternak 12 Ton', 12.00, 28.00, 'Sidoarjo', -7.44780000, 112.71830000, 'Malang', -7.96250000, 112.63040000, DATE_SUB(NOW(), INTERVAL 7 DAY), 'completed', 13, 8, 350000.00, 'SL-JC-9388-233', 'Muatan pakan ternak kering.', 4, DATE_SUB(NOW(), INTERVAL 6.8 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY)),

-- Order 25: Completed (Historical delivery for driver Cecep)
(25, 'PT Indofood CBP Tbk', 'Supermi & Indomie 800 Dus', 4.80, 15.00, 'Semarang', -6.96670000, 110.41670000, 'Solo', -7.57550000, 110.82430000, DATE_SUB(NOW(), INTERVAL 8 DAY), 'completed', 14, 7, 300000.00, 'SL-IC-9422-556', 'Dus indomie tidak ada penyok.', 4, DATE_SUB(NOW(), INTERVAL 7.8 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),

-- Order 26: Canceled (Cancelled historical delivery for driver Eko)
(26, 'PT Sayap Mas Utama (Wings Group)', 'Mie Sedaap 500 Dus', 3.00, 10.00, 'Jakarta', -6.20880000, 106.84560000, 'Tangerang', -6.17830000, 106.63190000, DATE_SUB(NOW(), INTERVAL 9 DAY), 'canceled', 9, 4, 200000.00, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 10 DAY)),

-- Order 27: Pending (Waiting dispatch assignment)
(27, 'PT Kalbe Farma Tbk', 'Multivitamin & Masker Medis 4 Pallet', 2.00, 7.00, 'Cikarang', -6.28560000, 107.17060000, 'Karawang', -6.30240000, 107.30510000, DATE_ADD(NOW(), INTERVAL 2 DAY), 'pending', NULL, NULL, 0.00, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 1 DAY)),

-- Order 28: Pending (Waiting dispatch assignment)
(28, 'PT Gudang Garam Tbk', 'Tembakau & Cengkeh 12 Ton', 12.00, 32.00, 'Temanggung', -7.31380000, 110.17420000, 'Kediri', -7.81720000, 112.01140000, DATE_ADD(NOW(), INTERVAL 1.5 DAY), 'pending', NULL, NULL, 0.00, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 1.5 DAY)),

-- Order 29: Completed (Historical delivery for driver Sutrisno)
(29, 'PT Unilever Indonesia Tbk', 'Rinso & Sunlight 15 Pallet', 9.00, 28.00, 'Cikarang', -6.28560000, 107.17060000, 'Surabaya', -7.25750000, 112.75210000, DATE_SUB(NOW(), INTERVAL 2 DAY), 'completed', 5, 1, 2100000.00, 'SL-UL-9021-998', 'Sunlight sabun cuci piring rapi.', 4, DATE_SUB(NOW(), INTERVAL 1.8 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),

-- Order 30: Completed (Historical delivery for driver Sutrisno)
(30, 'PT Nestle Indonesia', 'Susu Beruang (Bear Brand) 6 Ton', 6.00, 16.00, 'Pasuruan', -7.64440000, 112.90380000, 'Malang', -7.96250000, 112.63040000, DATE_SUB(NOW(), INTERVAL 1.5 DAY), 'completed', 5, 2, 400000.00, 'SL-NE-9812-701', 'Susu steril tidak ada penyok.', 4, DATE_SUB(NOW(), INTERVAL 1.3 DAY), DATE_SUB(NOW(), INTERVAL 1.5 DAY));


-- -----------------------------------------------------------
-- 2. Insert Pre-Trip Inspections for relevant dummy orders
-- -----------------------------------------------------------
INSERT INTO `pre_trip_inspections` (
  `order_id`, `vehicle_id`, `checked_by`, 
  `tires_ok`, `brakes_ok`, `lights_ok`, `engine_oil_ok`, `documents_ok`, 
  `status`, `notes`, `created_at`
) VALUES
-- Order 12 (Ready): Passed check
(12, 2, 4, 1, 1, 1, 1, 1, 'passed', 'Semua ban tebal, rem pakem, lampu berfungsi normal.', DATE_SUB(NOW(), INTERVAL 4.5 HOUR)),

-- Order 13 (Loading): Passed check
(13, 4, 4, 1, 1, 1, 1, 1, 'passed', 'Oli mesin bersih, air radiator penuh. Mesin halus.', DATE_SUB(NOW(), INTERVAL 1.5 HOUR)),

-- Order 14 (In Transit): Passed check
(14, 1, 4, 1, 1, 1, 1, 1, 'passed', 'Kondisi prima untuk perjalanan jauh luar kota.', DATE_SUB(NOW(), INTERVAL 9.5 HOUR)),

-- Order 15 (Arrived): Passed check
(15, 8, 4, 1, 1, 1, 1, 1, 'passed', 'Kondisi rem baik, oli penuh.', DATE_SUB(NOW(), INTERVAL 5.5 HOUR)),

-- Order 16 (POD Submitted): Passed check
(16, 5, 4, 1, 1, 1, 1, 1, 'passed', 'Wingbox berfungsi baik, lampu menyala terang.', DATE_SUB(NOW(), INTERVAL 17.5 HOUR)),

-- Order 17 (Inspect Failed): Failed check (Tires & Brakes bad)
(17, 3, 4, 0, 0, 1, 1, 1, 'failed', 'Ban belakang gundul sebelah kanan dan rem parkir los/tidak pakem.', DATE_SUB(NOW(), INTERVAL 3.5 HOUR)),

-- Order 18 (Completed): Passed check
(18, 1, 4, 1, 1, 1, 1, 1, 'passed', 'Armada siap jalan rute Bandung.', DATE_SUB(NOW(), INTERVAL 23.5 HOUR)),

-- Order 19 (Completed): Passed check
(19, 6, 4, 1, 1, 1, 1, 1, 'passed', 'Inspeksi ban, rem, lampu aman.', DATE_SUB(NOW(), INTERVAL 1.9 DAY)),

-- Order 20 (Completed): Passed check
(20, 2, 4, 1, 1, 1, 1, 1, 'passed', 'KIR aktif, fisik mobil prima.', DATE_SUB(NOW(), INTERVAL 2.9 DAY)),

-- Order 21 (Completed): Passed check
(21, 7, 4, 1, 1, 1, 1, 1, 'passed', 'CDD Box dalam kondisi bersih.', DATE_SUB(NOW(), INTERVAL 3.9 DAY)),

-- Order 22 (Completed): Passed check
(22, 5, 4, 1, 1, 1, 1, 1, 'passed', 'Baut roda kencang semua.', DATE_SUB(NOW(), INTERVAL 4.9 DAY)),

-- Order 23 (Completed): Passed check
(23, 4, 4, 1, 1, 1, 1, 1, 'passed', 'Lampu hazard dan sein oke.', DATE_SUB(NOW(), INTERVAL 5.9 DAY)),

-- Order 24 (Completed): Passed check
(24, 8, 4, 1, 1, 1, 1, 1, 'passed', 'Air wiper penuh, kaca bersih.', DATE_SUB(NOW(), INTERVAL 6.9 DAY)),

-- Order 25 (Completed): Passed check
(25, 7, 4, 1, 1, 1, 1, 1, 'passed', 'Dokumen lengkap dan aktif.', DATE_SUB(NOW(), INTERVAL 7.9 DAY)),

-- Order 29 (Completed): Passed check
(29, 1, 4, 1, 1, 1, 1, 1, 'passed', 'Tekanan ban pas untuk jalur tol.', DATE_SUB(NOW(), INTERVAL 1.9 DAY)),

-- Order 30 (Completed): Passed check
(30, 2, 4, 1, 1, 1, 1, 1, 'passed', 'Oli dan radiator aman.', DATE_SUB(NOW(), INTERVAL 1.4 DAY));


-- -----------------------------------------------------------
-- 3. Insert POD Submissions for relevant dummy orders
-- -----------------------------------------------------------
INSERT INTO `pod_submissions` (
  `order_id`, `uploaded_by`, `receiver_name`, `file_path`, `notes`, 
  `verified_by`, `verified_at`, `created_at`
) VALUES
-- Order 16: Submitted, waiting verification (verified_by NULL)
(16, 9, 'Bpk. Gunawan (Supervisor Logistik Cakung)', 'assets/uploads/pod/test_pod_proof.png', 'Karton utuh tidak ada basah, jumlah pas.', NULL, NULL, DATE_SUB(NOW(), INTERVAL 30 MINUTE)),

-- Order 18: Completed and verified by superadmin (ID=1)
(18, 5, 'Bpk. Ahmad (Gudang Bandung)', 'assets/uploads/pod/test_pod_proof.png', 'Muatan teh botol diterima dalam boks bersih.', 1, DATE_SUB(NOW(), INTERVAL 12 HOUR), DATE_SUB(NOW(), INTERVAL 13 HOUR)),

-- Order 19: Completed and verified by superadmin (ID=1)
(19, 7, 'Ibu Siska (Peternakan Sukabumi)', 'assets/uploads/pod/test_pod_proof.png', 'Pakan ayam diterima dalam karung kering.', 1, DATE_SUB(NOW(), INTERVAL 1.5 DAY), DATE_SUB(NOW(), INTERVAL 1.6 DAY)),

-- Order 20: Completed and verified by superadmin (ID=1)
(20, 8, 'Bpk. Dwi (Dealer Honda Jogja)', 'assets/uploads/pod/test_pod_proof.png', 'Motor Honda terkirim tanpa lecet.', 1, DATE_SUB(NOW(), INTERVAL 2.5 DAY), DATE_SUB(NOW(), INTERVAL 2.6 DAY)),

-- Order 21: Completed and verified by superadmin (ID=1)
(21, 9, 'Apoteker Rina (Kimia Farma Cirebon)', 'assets/uploads/pod/test_pod_proof.png', 'Alkes terkirim dengan boks higienis.', 1, DATE_SUB(NOW(), INTERVAL 3.5 DAY), DATE_SUB(NOW(), INTERVAL 3.6 DAY)),

-- Order 22: Completed and verified by superadmin (ID=1)
(22, 10, 'Bpk. Joko (Agen Rokok Solo)', 'assets/uploads/pod/test_pod_proof.png', 'Rokok Sampoerna diterima utuh.', 1, DATE_SUB(NOW(), INTERVAL 4.5 DAY), DATE_SUB(NOW(), INTERVAL 4.6 DAY)),

-- Order 23: Completed and verified by superadmin (ID=1)
(23, 11, 'Bpk. Hendro (Bengkel Toyota Jakarta)', 'assets/uploads/pod/test_pod_proof.png', 'Suku cadang Toyota diterima.', 1, DATE_SUB(NOW(), INTERVAL 5.5 DAY), DATE_SUB(NOW(), INTERVAL 5.6 DAY)),

-- Order 24: Completed and verified by superadmin (ID=1)
(24, 13, 'Ibu Laras (Distributor Malang)', 'assets/uploads/pod/test_pod_proof.png', 'Pakan ternak Japfa diterima.', 1, DATE_SUB(NOW(), INTERVAL 6.5 DAY), DATE_SUB(NOW(), INTERVAL 6.6 DAY)),

-- Order 25: Completed and verified by superadmin (ID=1)
(25, 14, 'Bpk. Rahmat (Gudang Solo)', 'assets/uploads/pod/test_pod_proof.png', 'Mie instan Indofood lengkap.', 1, DATE_SUB(NOW(), INTERVAL 7.5 DAY), DATE_SUB(NOW(), INTERVAL 7.6 DAY)),

-- Order 29: Completed and verified by superadmin (ID=1)
(29, 5, 'Ibu Wati (Distributor Surabaya)', 'assets/uploads/pod/test_pod_proof.png', 'Sabun cuci piring Rinso/Sunlight lengkap.', 1, DATE_SUB(NOW(), INTERVAL 1.5 DAY), DATE_SUB(NOW(), INTERVAL 1.6 DAY)),

-- Order 30: Completed and verified by superadmin (ID=1)
(30, 5, 'Bpk. Andi (Gudang Nestle Malang)', 'assets/uploads/pod/test_pod_proof.png', 'Susu Bear Brand diterima boks utuh.', 1, DATE_SUB(NOW(), INTERVAL 1.2 DAY), DATE_SUB(NOW(), INTERVAL 1.3 DAY));


-- -----------------------------------------------------------
-- 4. Sync Vehicle statuses based on order allocations
-- -----------------------------------------------------------
UPDATE `vehicles` SET `status` = 'active' WHERE `id` IN (1, 2, 4, 5, 6, 8);
UPDATE `vehicles` SET `status` = 'maintenance' WHERE `id` IN (3, 14);
UPDATE `vehicles` SET `status` = 'available' WHERE `id` IN (7, 9, 10, 11, 12, 13);
