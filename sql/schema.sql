-- StayCore Hotel Management System
-- Database: staycore_db
-- Run this in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS staycore_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE staycore_db;

-- Users / Guests / Staff
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('guest','staff','manager','admin') DEFAULT 'guest',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rooms
CREATE TABLE rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) UNIQUE NOT NULL,
    type ENUM('Standard','Deluxe','Suite','Executive') DEFAULT 'Standard',
    price_per_night DECIMAL(10,2) NOT NULL,
    status ENUM('Available','Reserved','Checked-In','Cleaning','Maintenance') DEFAULT 'Available',
    max_guests INT DEFAULT 2,
    description TEXT,
    amenities JSON,
    image_url VARCHAR(255),
    floor_size VARCHAR(20),
    bed_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reservations / Bookings
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
    status ENUM('Pending','Confirmed','Checked-In','Checked-Out','Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

-- Invoices / Billing
CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reservation_id INT NOT NULL,
    itemized_charges JSON,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('Paid','Pending','Overdue','Refunded') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
);

-- Housekeeping Tasks
CREATE TABLE housekeeping_tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT NOT NULL,
    assigned_to INT DEFAULT NULL,
    status ENUM('Pending','In Progress','Ready','Dirty') DEFAULT 'Pending',
    priority ENUM('Low','Medium','High') DEFAULT 'Medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Settings / Config
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample Data: Admin User
INSERT INTO users (name, email, password, role) VALUES
('Super Admin', 'admin@staycore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Manager Singh', 'manager@staycore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager'),
('Ravi Housekeeping', 'ravi@staycore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff'),
('Guest User', 'guest@staycore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guest');
-- Password for all sample users: password

-- Sample Data: Rooms
INSERT INTO rooms (room_number, type, price_per_night, status, max_guests, description, amenities, image_url, floor_size, bed_type) VALUES
('101', 'Standard', 4500.00, 'Available', 2, 'Cozy standard room with city view', '["Wi-Fi","AC","TV","Mini Bar"]', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600', '250 sq ft', 'Queen'),
('102', 'Deluxe', 7500.00, 'Available', 3, 'Spacious deluxe room with balcony', '["Wi-Fi","AC","TV","Mini Bar","Coffee Maker"]', 'https://images.unsplash.com/photo-1590487485178-16437f219dc7?w=600', '350 sq ft', 'King'),
('201', 'Suite', 12000.00, 'Cleaning', 4, 'Luxury suite with living area', '["Wi-Fi","AC","TV","Mini Bar","Coffee Maker","Bathtub"]', 'https://images.unsplash.com/photo-1618548608129-3dd3f86e697e?w=600', '550 sq ft', 'King'),
('202', 'Executive', 15000.00, 'Occupied', 4, 'Executive suite with skyline view', '["Wi-Fi","AC","TV","Mini Bar","Coffee Maker","Bathtub","Butler"]', 'https://images.unsplash.com/photo-1595172830759-7f040e0a0a2b?w=600', '650 sq ft', 'King'),
('301', 'Standard', 4500.00, 'Maintenance', 2, 'Standard room under maintenance', '["Wi-Fi","AC","TV"]', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600', '250 sq ft', 'Queen'),
('302', 'Deluxe', 7500.00, 'Available', 3, 'Deluxe room with garden view', '["Wi-Fi","AC","TV","Mini Bar"]', 'https://images.unsplash.com/photo-1590487485178-16437f219dc7?w=600', '350 sq ft', 'Queen');

-- Sample Settings
INSERT INTO settings (setting_key, setting_value) VALUES
('hotel_name', 'StayCore Hotel'),
('currency', 'INR'),
('tax_rate', '12'),
('timezone', 'Asia/Kolkata');