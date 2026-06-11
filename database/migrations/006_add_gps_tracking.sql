-- ============================================================
-- CI3 Logistics Operation System - GPS Tracking Migration & Seeds
-- ============================================================

USE `db_ci_apps`;

-- 1. Buat tabel pencatatan koordinat GPS Driver selama di perjalanan
CREATE TABLE IF NOT EXISTS `vehicle_location_logs` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) UNSIGNED NOT NULL,
  `driver_id` INT(11) UNSIGNED NOT NULL,
  `latitude` DECIMAL(10, 8) NOT NULL,
  `longitude` DECIMAL(11, 8) NOT NULL,
  `recorded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `fk_vll_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bersihkan log lama untuk order 10-30 agar seeder dapat berjalan ulang
DELETE FROM `vehicle_location_logs` WHERE `order_id` BETWEEN 10 AND 30;

-- 3. Tambahkan data koordinat GPS dummy untuk tracking jalur (bread crumbs)
-- Rute Order 14 (In Transit): Karawang -> Surabaya (Driver ID 5)
INSERT INTO `vehicle_location_logs` (`order_id`, `driver_id`, `latitude`, `longitude`, `recorded_at`) VALUES
(14, 5, -6.36010000, 107.28030000, DATE_SUB(NOW(), INTERVAL 8 HOUR)),
(14, 5, -6.40210000, 107.41200000, DATE_SUB(NOW(), INTERVAL 7.5 HOUR)),
(14, 5, -6.55840000, 107.64370000, DATE_SUB(NOW(), INTERVAL 7 HOUR)),
(14, 5, -6.72080000, 108.55590000, DATE_SUB(NOW(), INTERVAL 6 HOUR)),
(14, 5, -6.87030000, 109.03780000, DATE_SUB(NOW(), INTERVAL 5 HOUR)),
(14, 5, -6.86940000, 109.12500000, DATE_SUB(NOW(), INTERVAL 4.5 HOUR)),
(14, 5, -6.88860000, 109.67530000, DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(14, 5, -6.96670000, 110.41670000, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(14, 5, -6.99320000, 110.42030000, DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- Rute Order 15 (Arrived): Bekasi -> Solo (Driver ID 12)
INSERT INTO `vehicle_location_logs` (`order_id`, `driver_id`, `latitude`, `longitude`, `recorded_at`) VALUES
(15, 12, -6.28560000, 107.17060000, DATE_SUB(NOW(), INTERVAL 5 HOUR)),
(15, 12, -6.55840000, 107.44370000, DATE_SUB(NOW(), INTERVAL 4.5 HOUR)),
(15, 12, -6.72080000, 108.55590000, DATE_SUB(NOW(), INTERVAL 3.5 HOUR)),
(15, 12, -6.96670000, 110.41670000, DATE_SUB(NOW(), INTERVAL 2.5 HOUR)),
(15, 12, -7.33050000, 110.50840000, DATE_SUB(NOW(), INTERVAL 1.5 HOUR)),
(15, 12, -7.53120000, 110.59760000, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(15, 12, -7.55830000, 110.74230000, DATE_SUB(NOW(), INTERVAL 45 MINUTE));

-- Rute Order 16 (POD Submitted): Kediri -> Jakarta (Driver ID 9)
INSERT INTO `vehicle_location_logs` (`order_id`, `driver_id`, `latitude`, `longitude`, `recorded_at`) VALUES
(16, 9, -7.81720000, 112.01140000, DATE_SUB(NOW(), INTERVAL 17 HOUR)),
(16, 9, -7.57550000, 110.82430000, DATE_SUB(NOW(), INTERVAL 15 HOUR)),
(16, 9, -6.96670000, 110.41670000, DATE_SUB(NOW(), INTERVAL 12 HOUR)),
(16, 9, -6.72080000, 108.55590000, DATE_SUB(NOW(), INTERVAL 9 HOUR)),
(16, 9, -6.30240000, 107.30510000, DATE_SUB(NOW(), INTERVAL 5 HOUR)),
(16, 9, -6.19530000, 106.95110000, DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- Rute Order 18 (Completed): Jakarta -> Bandung (Driver ID 5)
INSERT INTO `vehicle_location_logs` (`order_id`, `driver_id`, `latitude`, `longitude`, `recorded_at`) VALUES
(18, 5, -6.20880000, 106.84560000, DATE_SUB(NOW(), INTERVAL 22 HOUR)),
(18, 5, -6.23830000, 106.97560000, DATE_SUB(NOW(), INTERVAL 21 HOUR)),
(18, 5, -6.28560000, 107.17060000, DATE_SUB(NOW(), INTERVAL 20 HOUR)),
(18, 5, -6.55840000, 107.44370000, DATE_SUB(NOW(), INTERVAL 18 HOUR)),
(18, 5, -6.91750000, 107.61910000, DATE_SUB(NOW(), INTERVAL 16 HOUR));
