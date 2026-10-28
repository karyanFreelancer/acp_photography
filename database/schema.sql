CREATE DATABASE IF NOT EXISTS acp_photography CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE acp_photography;

CREATE TABLE IF NOT EXISTS users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NULL,
 role ENUM('user','admin') NOT NULL DEFAULT 'user',
 provider VARCHAR(30) NULL,
 provider_id VARCHAR(190) NULL,
 avatar VARCHAR(500) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS remember_tokens (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 selector CHAR(18) NOT NULL UNIQUE,
 validator_hash CHAR(64) NOT NULL,
 expires_at DATETIME NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX(user_id),
 CONSTRAINT fk_remember_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL,
 phone VARCHAR(40) NOT NULL,
 service VARCHAR(100) NOT NULL,
 event_date DATE NULL,
 message TEXT NULL,
 status ENUM('new','contacted','confirmed','cancelled') NOT NULL DEFAULT 'new',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS portfolio_items (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(160) NOT NULL,
 category VARCHAR(100) NOT NULL,
 image_path VARCHAR(500) NOT NULL,
 is_published TINYINT(1) NOT NULL DEFAULT 1,
 sort_order INT NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO portfolio_items(title,category,image_path,sort_order) VALUES
('Golden Hour','Portrait','https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=1200&q=85',1),
('The Quiet Vow','Wedding','https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=85',2),
('City in Motion','Editorial','https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1200&q=85',3);

-- After importing this starter schema, also import database/migrations/2026_10_dashboard.sql.
