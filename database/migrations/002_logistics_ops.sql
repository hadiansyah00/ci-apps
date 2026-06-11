-- ============================================================
-- CI3 Logistics Operation System - Database Migration
-- Table Schema & Seeds for MVP
-- ============================================================

USE `db_ci_apps`;

-- -----------------------------------------------------------
-- Table: vehicles
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `plate_number` VARCHAR(20) NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `capacity_weight` DECIMAL(10,2) NOT NULL DEFAULT 0.00, -- in tons
  `capacity_volume` DECIMAL(10,2) NOT NULL DEFAULT 0.00, -- in CBM
  `kir_expiry` DATE NOT NULL,
  `tax_expiry` DATE NOT NULL,
  `status` ENUM('available', 'active', 'maintenance') NOT NULL DEFAULT 'available',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plate_number` (`plate_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: orders
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(150) NOT NULL,
  `cargo_description` TEXT NOT NULL,
  `weight` DECIMAL(10,2) NOT NULL DEFAULT 0.00, -- in tons
  `volume` DECIMAL(10,2) NOT NULL DEFAULT 0.00, -- in CBM
  `origin` VARCHAR(255) NOT NULL,
  `origin_latitude` DECIMAL(10, 8) DEFAULT NULL,
  `origin_longitude` DECIMAL(11, 8) DEFAULT NULL,
  `destination` VARCHAR(255) NOT NULL,
  `destination_latitude` DECIMAL(10, 8) DEFAULT NULL,
  `destination_longitude` DECIMAL(11, 8) DEFAULT NULL,
  `eta` DATETIME NOT NULL,
  `status` ENUM('pending', 'allocated', 'inspect_failed', 'ready', 'loading', 'in_transit', 'arrived', 'pod_submitted', 'completed', 'canceled') NOT NULL DEFAULT 'pending',
  `driver_id` INT(11) UNSIGNED DEFAULT NULL,
  `vehicle_id` INT(11) UNSIGNED DEFAULT NULL,
  `uang_jalan` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `seal_number` VARCHAR(50) DEFAULT NULL,
  `loading_notes` TEXT DEFAULT NULL,
  `loading_verified_by` INT(11) UNSIGNED DEFAULT NULL,
  `loading_verified_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `driver_id` (`driver_id`),
  KEY `vehicle_id` (`vehicle_id`),
  KEY `loading_verified_by` (`loading_verified_by`),
  CONSTRAINT `fk_orders_driver` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_orders_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_orders_loading_verifier` FOREIGN KEY (`loading_verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: pre_trip_inspections
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pre_trip_inspections` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) UNSIGNED NOT NULL,
  `vehicle_id` INT(11) UNSIGNED NOT NULL,
  `checked_by` INT(11) UNSIGNED NOT NULL,
  `tires_ok` TINYINT(1) NOT NULL DEFAULT 1,
  `brakes_ok` TINYINT(1) NOT NULL DEFAULT 1,
  `lights_ok` TINYINT(1) NOT NULL DEFAULT 1,
  `engine_oil_ok` TINYINT(1) NOT NULL DEFAULT 1,
  `documents_ok` TINYINT(1) NOT NULL DEFAULT 1,
  `status` ENUM('passed', 'failed') NOT NULL DEFAULT 'passed',
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `vehicle_id` (`vehicle_id`),
  KEY `checked_by` (`checked_by`),
  CONSTRAINT `fk_pti_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pti_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pti_checker` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: pod_submissions
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pod_submissions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) UNSIGNED NOT NULL,
  `uploaded_by` INT(11) UNSIGNED NOT NULL,
  `receiver_name` VARCHAR(150) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `verified_by` INT(11) UNSIGNED DEFAULT NULL,
  `verified_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `uploaded_by` (`uploaded_by`),
  KEY `verified_by` (`verified_by`),
  CONSTRAINT `fk_pod_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pod_uploader` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pod_verifier` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA & PERMISSIONS
-- ============================================================

-- Insert New Roles
INSERT INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(4, 'Checker Armada', 'checker', 'Armada physical condition checker'),
(5, 'Driver', 'driver', 'Shipment transport driver'),
(6, 'Manager', 'manager', 'Executive management & logistics viewer');

-- Insert New Permissions
INSERT INTO `permissions` (`name`, `slug`, `module`) VALUES
-- Vehicle Management
('View Vehicles', 'fleets.view', 'fleets'),
('Manage Vehicles', 'fleets.manage', 'fleets'),

-- Order Management
('View Logistics Orders', 'orders.view', 'orders'),
('Create Logistics Order', 'orders.create', 'orders'),
('Edit Logistics Order', 'orders.edit', 'orders'),
('Delete Logistics Order', 'orders.delete', 'orders'),

-- Dispatch Operations
('Assign Fleet and Driver', 'dispatch.assign', 'dispatch'),
('Print Surat Jalan', 'dispatch.print-sj', 'dispatch'),

-- Inspections
('View Inspections log', 'inspections.view', 'inspections'),
('Create Inspection checklist', 'inspections.create', 'inspections'),
('Verify Loaded Cargo', 'loading.verify', 'inspections'),

-- POD
('Upload POD Proof', 'pod.upload', 'pod'),
('Verify POD Proof', 'pod.verify', 'pod'),

-- Driver Portal
('Portal Driver / Tugas Saya', 'driver.tasks', 'driver'),

-- Reports
('View Executive Dashboard', 'dashboard.executive', 'reports'),
('View Executive Reports', 'reports.view', 'reports');

-- Assign Permissions to Super Admin (role_id=1)
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, id FROM `permissions` WHERE `slug` IN (
  'fleets.view', 'fleets.manage',
  'orders.view', 'orders.create', 'orders.edit', 'orders.delete',
  'dispatch.assign', 'dispatch.print-sj',
  'inspections.view', 'inspections.create', 'loading.verify',
  'pod.upload', 'pod.verify', 'driver.tasks',
  'dashboard.executive', 'reports.view'
);

-- Assign Permissions to Admin (role_id=2)
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 2, id FROM `permissions` WHERE `slug` IN (
  'fleets.view',
  'orders.view', 'orders.create', 'orders.edit', 'orders.delete',
  'dispatch.assign', 'dispatch.print-sj',
  'inspections.view',
  'pod.verify', 'driver.tasks'
);

-- Assign Permissions to Checker (role_id=4)
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 4, id FROM `permissions` WHERE `slug` IN (
  'dashboard.view',
  'fleets.view',
  'orders.view',
  'inspections.view', 'inspections.create',
  'loading.verify'
);

-- Assign Permissions to Driver (role_id=5)
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 5, id FROM `permissions` WHERE `slug` IN (
  'pod.upload', 'driver.tasks'
);

-- Assign Permissions to Manager (role_id=6)
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 6, id FROM `permissions` WHERE `slug` IN (
  'fleets.view',
  'orders.view',
  'inspections.view',
  'dashboard.executive', 'reports.view'
);

-- Insert Default Vehicles
INSERT INTO `vehicles` (`id`, `plate_number`, `type`, `capacity_weight`, `capacity_volume`, `kir_expiry`, `tax_expiry`, `status`) VALUES
(1, 'B 9021 TNP', 'Tronton Wingbox', 15.00, 45.00, DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 8 MONTH), 'available'),
(2, 'B 9812 TNP', 'Fuso Box', 8.00, 25.00, DATE_ADD(CURRENT_DATE, INTERVAL 3 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 10 MONTH), 'available'),
(3, 'B 9554 TNP', 'CDD Box', 4.00, 14.00, DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 2 MONTH), 'maintenance'),
(4, 'B 9211 TNP', 'CDD Box', 4.00, 14.00, DATE_ADD(CURRENT_DATE, INTERVAL 4 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 9 MONTH), 'available'),
(5, 'B 9132 TNP', 'Fuso Box', 8.00, 25.00, DATE_ADD(CURRENT_DATE, INTERVAL 5 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 11 MONTH), 'available'),
(6, 'B 9977 TNP', 'Tronton Wingbox', 15.00, 45.00, DATE_ADD(CURRENT_DATE, INTERVAL 7 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 5 MONTH), 'available'),
(7, 'B 9422 TNP', 'CDD Box', 4.00, 14.00, DATE_ADD(CURRENT_DATE, INTERVAL 2 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), 'available'),
(8, 'B 9388 TNP', 'Fuso Box', 8.00, 25.00, DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 10 MONTH), 'available'),
(9, 'B 9102 TNP', 'CDE Box', 2.00, 6.00, DATE_ADD(CURRENT_DATE, INTERVAL 5 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 7 MONTH), 'available'),
(10, 'B 9055 TNP', 'Blind Van', 1.00, 3.50, DATE_ADD(CURRENT_DATE, INTERVAL 8 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 11 MONTH), 'available'),
(11, 'B 9722 TNP', 'CDD Long', 5.00, 18.00, DATE_ADD(CURRENT_DATE, INTERVAL 4 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), 'available'),
(12, 'B 9631 TNP', 'Container 20ft', 20.00, 33.00, DATE_ADD(CURRENT_DATE, INTERVAL 9 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'available'),
(13, 'B 9411 TNP', 'Container 40ft', 28.00, 67.00, DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), DATE_ADD(CURRENT_DATE, INTERVAL 10 MONTH), 'available'),
(14, 'B 9001 TNP', 'Tronton Wingbox', 15.00, 45.00, DATE_SUB(CURRENT_DATE, INTERVAL 10 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 4 MONTH), 'maintenance');

-- Insert Default Users for Logistics Roles
-- Password for checkers (budi, joko): Checker@123
-- Password for driver users (sutrisno, bambang, agus, eko, dedi, maman, tono, budis, cecep): Driver@123
-- Password for hendra: Manager@123
INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `role_id`, `is_active`) VALUES
(4, 'Budi Utomo (Checker)', 'budi@example.com', 'budi', '$2y$10$EN8618XEobDpD1e/iQAnfubrMqVALff4d2M30kME0yKEM4dv1rHz.', 4, 1),
(5, 'Sutrisno (Driver)', 'sutrisno@example.com', 'sutrisno', '$2y$10$QVqhZcYs2RwHQJ/VnYgkf.MwetOv7tmfAJTDDNHfA0AfMhVK28nV.', 5, 1),
(6, 'Hendra Wijaya (Manager)', 'hendra@example.com', 'hendra', '$2y$10$CcBzZHTi/7AqNETYnItZ4.NiZo7QDGKP/i/JROoNA86R0pZ8ZHERq', 6, 1),
(7, 'Bambang Hermawan (Driver)', 'bambang@example.com', 'bambang', '$2y$10$QVqhZcYs2RwHQJ/VnYgkf.MwetOv7tmfAJTDDNHfA0AfMhVK28nV.', 5, 1),
(8, 'Agus Prayitno (Driver)', 'agus@example.com', 'agus', '$2y$10$QVqhZcYs2RwHQJ/VnYgkf.MwetOv7tmfAJTDDNHfA0AfMhVK28nV.', 5, 1),
(9, 'Eko Susilo (Driver)', 'eko@example.com', 'eko', '$2y$10$QVqhZcYs2RwHQJ/VnYgkf.MwetOv7tmfAJTDDNHfA0AfMhVK28nV.', 5, 1),
(10, 'Dedi Setiawan (Driver)', 'dedi@example.com', 'dedi', '$2y$10$QVqhZcYs2RwHQJ/VnYgkf.MwetOv7tmfAJTDDNHfA0AfMhVK28nV.', 5, 1),
(11, 'Maman Abdurrahman (Driver)', 'maman@example.com', 'maman', '$2y$10$QVqhZcYs2RwHQJ/VnYgkf.MwetOv7tmfAJTDDNHfA0AfMhVK28nV.', 5, 1),
(12, 'Tono (Driver)', 'tono@example.com', 'tono', '$2y$10$QVqhZcYs2RwHQJ/VnYgkf.MwetOv7tmfAJTDDNHfA0AfMhVK28nV.', 5, 1),
(13, 'Budi Santoso (Driver)', 'budis@example.com', 'budis', '$2y$10$QVqhZcYs2RwHQJ/VnYgkf.MwetOv7tmfAJTDDNHfA0AfMhVK28nV.', 5, 1),
(14, 'Cecep (Driver)', 'cecep@example.com', 'cecep', '$2y$10$QVqhZcYs2RwHQJ/VnYgkf.MwetOv7tmfAJTDDNHfA0AfMhVK28nV.', 5, 1),
(15, 'Joko Susilo (Checker)', 'joko@example.com', 'joko', '$2y$10$EN8618XEobDpD1e/iQAnfubrMqVALff4d2M30kME0yKEM4dv1rHz.', 4, 1);
