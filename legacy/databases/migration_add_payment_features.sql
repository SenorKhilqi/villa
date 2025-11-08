-- Migration: Add QRIS automation and refund features
-- Run this after importing villa_panjalu.sql

-- Add new columns to bookings table for payment automation and refunds
ALTER TABLE `bookings`
  ADD COLUMN `qr_code_url` VARCHAR(500) NULL AFTER `payment_method`,
  ADD COLUMN `payment_reference` VARCHAR(100) NULL AFTER `qr_code_url`,
  ADD COLUMN `paid_at` DATETIME NULL AFTER `payment_reference`,
  ADD COLUMN `refund_status` ENUM('none', 'requested', 'approved', 'rejected', 'completed') DEFAULT 'none' AFTER `paid_at`,
  ADD COLUMN `refund_amount` DECIMAL(10,2) NULL AFTER `refund_status`,
  ADD COLUMN `refund_reason` TEXT NULL AFTER `refund_amount`,
  ADD COLUMN `refund_requested_at` DATETIME NULL AFTER `refund_reason`,
  ADD COLUMN `refund_processed_at` DATETIME NULL AFTER `refund_requested_at`,
  ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `refund_processed_at`,
  ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Update existing payment_status enum to include more states
ALTER TABLE `bookings`
  MODIFY COLUMN `payment_status` ENUM('pending', 'awaiting_payment', 'completed', 'expired', 'cancelled') DEFAULT 'pending';

-- Create payment_logs table for tracking all payment events
CREATE TABLE IF NOT EXISTS `payment_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) NOT NULL,
  `event_type` VARCHAR(50) NOT NULL COMMENT 'e.g., qr_generated, payment_received, refund_requested',
  `event_data` TEXT NULL COMMENT 'JSON data for the event',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `payment_logs_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create refunds table for detailed refund tracking
CREATE TABLE IF NOT EXISTS `refunds` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) NOT NULL,
  `refund_amount` DECIMAL(10,2) NOT NULL,
  `refund_reason` TEXT NOT NULL,
  `refund_status` ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
  `requested_by_user_id` INT(11) NOT NULL,
  `processed_by_admin_id` INT(11) NULL,
  `admin_notes` TEXT NULL,
  `requested_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `processed_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `requested_by_user_id` (`requested_by_user_id`),
  CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `refunds_ibfk_2` FOREIGN KEY (`requested_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add index for better query performance
CREATE INDEX idx_booking_date ON bookings(booking_date);
CREATE INDEX idx_payment_status ON bookings(payment_status);
CREATE INDEX idx_refund_status ON bookings(refund_status);
