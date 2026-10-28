-- ACP Photography additive migration. Backup database before applying.
CREATE TABLE IF NOT EXISTS service_packages (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(190) NOT NULL, category VARCHAR(120) DEFAULT NULL, description TEXT DEFAULT NULL,
 price DECIMAL(12,2) NOT NULL DEFAULT 0.00, is_active TINYINT(1) NOT NULL DEFAULT 1,
 sort_order INT NOT NULL DEFAULT 0, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 KEY idx_service_active_order (is_active,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS site_pages (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(190) NOT NULL, slug VARCHAR(190) NOT NULL UNIQUE, content LONGTEXT DEFAULT NULL,
 seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(500) DEFAULT NULL,
 is_published TINYINT(1) NOT NULL DEFAULT 0, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS admin_notifications (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, title VARCHAR(190) NOT NULL,
 message TEXT DEFAULT NULL, severity ENUM('info','success','warning','danger') NOT NULL DEFAULT 'info',
 is_read TINYINT(1) NOT NULL DEFAULT 0, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_notification_read (is_read,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS admin_login_attempts (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, email VARCHAR(190) NOT NULL,
 ip_hash CHAR(64) NOT NULL, attempted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_login_attempt (email,attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Existing columns/tables from acp_photography.sql are preserved.

-- If the existing install has an admin public user, copy that hash into the separate admin table once.
INSERT INTO admin_users (name,email,password_hash,role)
SELECT u.name,u.email,u.password_hash,'owner' FROM users u
WHERE u.role='admin' AND u.password_hash IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM admin_users a WHERE a.email=u.email)
LIMIT 1;

CREATE TABLE IF NOT EXISTS gallery_photos (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, title VARCHAR(190) NOT NULL, category VARCHAR(120) DEFAULT NULL,
 event_name VARCHAR(190) DEFAULT NULL, image_path TEXT NOT NULL, caption VARCHAR(500) DEFAULT NULL, alt_text VARCHAR(255) DEFAULT NULL,
 is_published TINYINT(1) NOT NULL DEFAULT 0, sort_order INT NOT NULL DEFAULT 0,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 KEY idx_gallery_publish_order (is_published,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
