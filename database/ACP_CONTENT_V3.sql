-- Run AFTER ACP_CONTENT_V2.sql. Safe to run more than once on MySQL 5.7+ / MariaDB.
SET @column_exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'acp_content_items' AND COLUMN_NAME = 'category');
SET @sql := IF(@column_exists=0, 'ALTER TABLE acp_content_items ADD COLUMN category VARCHAR(32) NOT NULL DEFAULT \'weddings\' AFTER kind', 'SELECT \'category already exists\' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

