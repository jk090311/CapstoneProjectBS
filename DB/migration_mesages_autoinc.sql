-- Migration: make mesages.msg_id auto-increment primary key
-- Run this in your MySQL client (phpMyAdmin or mysql CLI) while the app is offline.

ALTER TABLE `mesages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- Optional: add read flag and timestamp (safe if columns don't exist)
-- ALTER TABLE `mesages` ADD COLUMN IF NOT EXISTS `is_read` TINYINT(1) NOT NULL DEFAULT 0;
-- ALTER TABLE `mesages` ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
