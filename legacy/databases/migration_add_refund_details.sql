-- Migration: Add Refund Details Fields
-- Add columns for refund payment details (account holder, method, account number)

ALTER TABLE `refunds`
  ADD COLUMN `refund_method` VARCHAR(50) NULL AFTER `refund_reason`,
  ADD COLUMN `account_holder_name` VARCHAR(100) NULL AFTER `refund_method`,
  ADD COLUMN `account_number` VARCHAR(100) NULL AFTER `account_holder_name`;

-- Add same columns to bookings table for quick reference
ALTER TABLE `bookings`
  ADD COLUMN `refund_method` VARCHAR(50) NULL AFTER `refund_reason`,
  ADD COLUMN `refund_account_holder` VARCHAR(100) NULL AFTER `refund_method`,
  ADD COLUMN `refund_account_number` VARCHAR(100) NULL AFTER `refund_account_holder`;
