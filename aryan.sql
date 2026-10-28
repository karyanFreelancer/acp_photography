
CREATE TABLE IF NOT EXISTS `gallery_photos` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(190) NOT NULL,
    `category` VARCHAR(120) DEFAULT NULL,
    `event_name` VARCHAR(190) DEFAULT NULL,
    `image_path` TEXT NOT NULL,
    `caption` VARCHAR(500) DEFAULT NULL,
    `alt_text` VARCHAR(255) DEFAULT NULL,
    `is_published` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_gallery_publish_order` (`is_published`, `sort_order`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
