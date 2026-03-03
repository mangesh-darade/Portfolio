-- Add forgot-password token columns to admin_users table
-- Run this SQL in phpMyAdmin or your MySQL client

ALTER TABLE `admin_users`
    ADD COLUMN IF NOT EXISTS `reset_token`      VARCHAR(64)  DEFAULT NULL AFTER `last_login`,
    ADD COLUMN IF NOT EXISTS `reset_expires_at` DATETIME     DEFAULT NULL AFTER `reset_token`;

-- Optional: add an index for fast token lookup
CREATE INDEX IF NOT EXISTS `idx_reset_token` ON `admin_users` (`reset_token`);
