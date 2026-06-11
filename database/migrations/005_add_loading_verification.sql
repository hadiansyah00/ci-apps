-- ============================================================
-- CI3 Logistics Operation System - Migration 005
-- Add Loading Status, Loading Verification Columns, & Permissions
-- ============================================================

USE `db_ci_apps`;

-- 1. Insert new permission 'loading.verify' if not exists
INSERT IGNORE INTO `permissions` (`name`, `slug`, `module`) VALUES
('Verify Loaded Cargo', 'loading.verify', 'inspections');

-- 5. Revoke 'inspections.create' from Driver (role_id = 5)
DELETE FROM `role_permissions` 
WHERE `role_id` = 5 
  AND `permission_id` = (SELECT `id` FROM `permissions` WHERE `slug` = 'inspections.create' LIMIT 1);

-- 6. Grant 'orders.view' and 'loading.verify' to Checker (role_id = 4)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 4, id FROM `permissions` 
WHERE `slug` IN ('orders.view', 'loading.verify');

-- 7. Grant 'loading.verify' to Super Admin (role_id = 1)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, id FROM `permissions` 
WHERE `slug` = 'loading.verify';
