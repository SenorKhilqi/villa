-- ============================================================================
-- Villa Panjalu - Complete Database Schema
-- ============================================================================
-- Version: 2.0 (with Payment Gateway & Refund System)
-- Created: November 8, 2025
-- Description: All-in-one schema including payment automation and refund features
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================================================
-- Database: villa_panjalu
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `villa_panjalu` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `villa_panjalu`;

-- ============================================================================
-- Table: users
-- ============================================================================

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Bcrypt hashed password',
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Default users
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', '$2y$10$H4qDnOb4N/s0/jTcVqK0e.8dEsa.ifWxtyHgD/ONzG5J3zxgVOBLG', 'admin'), -- password: admin123
('khilqi', '$2y$10$ej7U65j/AKY2RtRp3Iofouk94EmBaJt7N8CpcYXe3Uf7xNIKoKMza', 'user'), -- password: 12345678
('user', '$2y$10$N1nHSj1SrhvjBJ7rEl7pE.CzdoXSymvvuow217fX3fNWQbzE3Ll76', 'user');   -- password: password

-- ============================================================================
-- Table: villas
-- ============================================================================

CREATE TABLE `villas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `description` TEXT NULL,
  `capacity` INT(11) DEFAULT 4,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample villas
INSERT INTO `villas` (`name`, `price`, `image`, `capacity`) VALUES
('Villa Kayu Hujung', 750000.00, 'kayu_hujung.jpg', 6),
('Villa Bata Dukuh', 500000.00, 'bata_dukuh.jpg', 4);

-- ============================================================================
-- Table: bookings (with payment automation & refund features)
-- ============================================================================

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `villa_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  
  -- Payment Information
  `payment_status` enum('pending','awaiting_payment','completed','expired','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'qris, dana, ovo, gopay, whatsapp',
  `qr_code_url` varchar(500) NULL COMMENT 'Midtrans QR code URL',
  `payment_reference` varchar(100) NULL COMMENT 'Midtrans order_id',
  `paid_at` datetime NULL,
  
  -- Refund Information
  `refund_status` enum('none','requested','approved','rejected','completed') DEFAULT 'none',
  `refund_amount` decimal(10,2) NULL,
  `refund_reason` text NULL,
  `refund_method` varchar(50) NULL COMMENT 'dana, ovo, gopay, bank_bca, bank_mandiri, bank_bri, bank_bni',
  `refund_account_holder` varchar(100) NULL,
  `refund_account_number` varchar(100) NULL,
  `refund_requested_at` datetime NULL,
  `refund_processed_at` datetime NULL,
  
  -- Timestamps
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `villa_id` (`villa_id`),
  KEY `idx_booking_date` (`booking_date`),
  KEY `idx_payment_status` (`payment_status`),
  KEY `idx_refund_status` (`refund_status`),
  
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`villa_id`) REFERENCES `villas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================================
-- Table: payment_logs (tracking payment events)
-- ============================================================================

CREATE TABLE `payment_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL COMMENT 'qr_generated, payment_received, payment_expired, webhook_received',
  `event_data` text NULL COMMENT 'JSON data from webhook or event details',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `idx_event_type` (`event_type`),
  
  CONSTRAINT `payment_logs_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================================
-- Table: refunds (detailed refund tracking)
-- ============================================================================

CREATE TABLE `refunds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL,
  `refund_reason` text NOT NULL,
  `refund_method` varchar(50) NULL COMMENT 'Payment method for refund',
  `account_holder_name` varchar(100) NULL,
  `account_number` varchar(100) NULL,
  
  -- Status & Processing
  `refund_status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `requested_by_user_id` int(11) NOT NULL,
  `processed_by_admin_id` int(11) NULL,
  `admin_notes` text NULL COMMENT 'Admin notes for rejection or approval',
  
  -- Timestamps
  `requested_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `processed_at` datetime NULL,
  
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `requested_by_user_id` (`requested_by_user_id`),
  KEY `idx_refund_status` (`refund_status`),
  
  CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `refunds_ibfk_2` FOREIGN KEY (`requested_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================================
-- Views (for easier queries)
-- ============================================================================

-- View: Active Bookings with User & Villa Info
CREATE OR REPLACE VIEW `v_active_bookings` AS
SELECT 
  b.id AS booking_id,
  u.username,
  u.role,
  v.name AS villa_name,
  v.price,
  b.booking_date,
  b.payment_status,
  b.payment_method,
  b.refund_status,
  b.paid_at,
  b.created_at
FROM bookings b
JOIN users u ON b.user_id = u.id
JOIN villas v ON b.villa_id = v.id
WHERE b.payment_status != 'cancelled'
ORDER BY b.booking_date DESC;

-- View: Pending Refunds with Details
CREATE OR REPLACE VIEW `v_pending_refunds` AS
SELECT 
  r.id AS refund_id,
  r.booking_id,
  r.refund_amount,
  r.refund_reason,
  r.refund_method,
  r.account_holder_name,
  r.account_number,
  r.requested_at,
  u.username,
  v.name AS villa_name,
  b.booking_date,
  b.payment_method
FROM refunds r
JOIN bookings b ON r.booking_id = b.id
JOIN users u ON r.requested_by_user_id = u.id
JOIN villas v ON b.villa_id = v.id
WHERE r.refund_status = 'pending'
ORDER BY r.requested_at ASC;

-- ============================================================================
-- Indexes for Performance Optimization
-- ============================================================================

-- Already created above with tables, but listed here for reference:
-- bookings: idx_booking_date, idx_payment_status, idx_refund_status
-- payment_logs: idx_event_type
-- refunds: idx_refund_status

-- ============================================================================
-- Triggers (optional - for audit trail)
-- ============================================================================

DELIMITER $$

-- Trigger: Log when booking payment status changes
CREATE TRIGGER `log_payment_status_change` 
AFTER UPDATE ON `bookings`
FOR EACH ROW
BEGIN
  IF OLD.payment_status != NEW.payment_status THEN
    INSERT INTO payment_logs (booking_id, event_type, event_data)
    VALUES (NEW.id, 'status_changed', CONCAT('{"old":"', OLD.payment_status, '","new":"', NEW.payment_status, '"}'));
  END IF;
END$$

-- Trigger: Log when refund status changes
CREATE TRIGGER `log_refund_status_change`
AFTER UPDATE ON `refunds`
FOR EACH ROW
BEGIN
  IF OLD.refund_status != NEW.refund_status THEN
    INSERT INTO payment_logs (booking_id, event_type, event_data)
    VALUES (NEW.booking_id, 'refund_status_changed', CONCAT('{"old":"', OLD.refund_status, '","new":"', NEW.refund_status, '"}'));
  END IF;
END$$

DELIMITER ;

-- ============================================================================
-- Sample Data (optional - comment out for production)
-- ============================================================================

-- Sample booking
-- INSERT INTO `bookings` (`user_id`, `villa_id`, `booking_date`, `payment_status`, `payment_method`) 
-- VALUES (1, 1, '2025-12-25', 'completed', 'qris');

-- ============================================================================
-- Useful Queries (for reference)
-- ============================================================================

/*
-- Get all bookings for a user
SELECT * FROM v_active_bookings WHERE username = 'khilqi';

-- Get pending refunds for admin
SELECT * FROM v_pending_refunds;

-- Get payment history for a booking
SELECT * FROM payment_logs WHERE booking_id = 1 ORDER BY created_at DESC;

-- Revenue report
SELECT 
  DATE_FORMAT(paid_at, '%Y-%m') AS month,
  COUNT(*) AS total_bookings,
  SUM(v.price) AS total_revenue
FROM bookings b
JOIN villas v ON b.villa_id = v.id
WHERE payment_status = 'completed'
GROUP BY month
ORDER BY month DESC;

-- Refund statistics
SELECT 
  refund_status,
  COUNT(*) AS count,
  SUM(refund_amount) AS total_amount
FROM refunds
GROUP BY refund_status;
*/

-- ============================================================================
-- Completion
-- ============================================================================

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ============================================================================
-- End of villa_panjalu_complete.sql
-- ============================================================================
