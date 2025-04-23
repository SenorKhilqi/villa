CREATE DATABASE villa_panjalu;

USE villa_panjalu;

-- Tabel users untuk menyimpan data pengguna
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL
);

-- Tabel villas untuk menyimpan data villa
CREATE TABLE villas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Tambahkan data dua villa
INSERT INTO villas (name) VALUES ('Villa 1'), ('Villa 2');

-- Tabel bookings untuk menyimpan data pemesanan villa
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    villa_id INT,
    booking_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (villa_id) REFERENCES villas(id) ON DELETE CASCADE
);
USE villa_panjalu;

ALTER TABLE bookings 
ADD COLUMN payment_status ENUM('pending', 'completed') DEFAULT 'pending',
ADD COLUMN payment_method VARCHAR(50) DEFAULT NULL;

USE villa_panjalu;

ALTER TABLE villas 
ADD COLUMN price DECIMAL(10, 2) NOT NULL DEFAULT 0.00;

-- Update harga villa sebagai contoh
UPDATE villas SET price = 500000 WHERE name = 'Villa 1';
UPDATE villas SET price = 750000 WHERE name = 'Villa 2';

USE villa_panjalu;

ALTER TABLE villas 
ADD COLUMN image VARCHAR(255) NULL;

-- Masukkan contoh gambar untuk masing-masing villa
UPDATE villas SET image = 'villa1.jpg' WHERE name = 'Villa 1';
UPDATE villas SET image = 'villa2.jpg' WHERE name = 'Villa 2';
