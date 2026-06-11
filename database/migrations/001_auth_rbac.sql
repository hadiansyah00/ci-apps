-- ============================================================
-- CI3 Auth & RBAC - Database Migration
-- Compatible with MySQL/MariaDB
-- Run this script to setup the database
-- ============================================================

CREATE DATABASE IF NOT EXISTS `db_ci_apps` 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `db_ci_apps`;

-- -----------------------------------------------------------
-- Table: roles
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: permissions
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `module` VARCHAR(100) NOT NULL DEFAULT 'general',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: users
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role_id` INT(11) UNSIGNED NOT NULL DEFAULT 3,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `login_attempts` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  `locked_until` TIMESTAMP NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: role_permissions
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` INT(11) UNSIGNED NOT NULL,
  `permission_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_role_permission` (`role_id`,`permission_id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rp_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: login_logs (optional - untuk audit)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `login_logs` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('success','failed','locked') NOT NULL DEFAULT 'failed',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Roles
INSERT INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Super Admin', 'super-admin', 'Full access to all features and settings'),
(2, 'Admin', 'admin', 'Administrative access with limited system settings'),
(3, 'User', 'user', 'Regular user with basic access');

-- Permissions
INSERT INTO `permissions` (`name`, `slug`, `module`) VALUES
-- Dashboard
('View Dashboard', 'dashboard.view', 'dashboard'),

-- User Management
('View Users', 'users.view', 'users'),
('Create User', 'users.create', 'users'),
('Edit User', 'users.edit', 'users'),
('Delete User', 'users.delete', 'users'),
('Toggle User Status', 'users.toggle', 'users'),
('Reset User Password', 'users.reset-password', 'users'),

-- Role Management
('View Roles', 'roles.view', 'roles'),
('Create Role', 'roles.create', 'roles'),
('Edit Role', 'roles.edit', 'roles'),
('Delete Role', 'roles.delete', 'roles'),
('Assign Permissions to Role', 'roles.assign-permissions', 'roles'),

-- Permission Management
('View Permissions', 'permissions.view', 'permissions'),
('Create Permission', 'permissions.create', 'permissions'),
('Edit Permission', 'permissions.edit', 'permissions'),
('Delete Permission', 'permissions.delete', 'permissions'),

-- Profile
('View Profile', 'profile.view', 'profile'),
('Edit Profile', 'profile.edit', 'profile');

-- Role Permissions: Super Admin (role_id=1) gets ALL permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, id FROM `permissions`;

-- Role Permissions: Admin (role_id=2) gets most permissions except system
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 2, id FROM `permissions` 
WHERE `slug` IN (
  'dashboard.view',
  'users.view', 'users.create', 'users.edit', 'users.toggle', 'users.reset-password',
  'roles.view',
  'permissions.view',
  'profile.view', 'profile.edit'
);

-- Role Permissions: User (role_id=3) gets basic permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 3, id FROM `permissions`
WHERE `slug` IN ('dashboard.view', 'profile.view', 'profile.edit');

-- Users Seed Data
-- superadmin password: SuperAdmin@123
-- admin password:      Admin@123
-- user password:       User@123
INSERT INTO `users` (`name`, `email`, `username`, `password`, `role_id`, `is_active`) VALUES
('Super Administrator', 'superadmin@example.com', 'superadmin', '$2y$10$Y2S6vyhdDFLzN7DnrIWMaeiBOsZukR/XXSjf4VufOVqQI9xBlo4i.', 1, 1),
('Administrator', 'admin@example.com', 'admin', '$2y$10$gsG0o/02FDBMdXnVip2T5ORRDTMqwAFGNLqwkGVeLxdAqsC8wTq0u', 2, 1),
('Regular User', 'user@example.com', 'user', '$2y$10$I1NxQ1oRAg8c7RDccamYTeDkvo7fhuhw2E3FsAqklX7yizgscgmmO', 3, 1);

-- ============================================================
-- END OF MIGRATION
-- Hashes generated with password_hash($pass, PASSWORD_BCRYPT) on PHP 7.4
-- To generate new hashes: php -r "echo password_hash('YourPassword', PASSWORD_BCRYPT);"
-- ============================================================

