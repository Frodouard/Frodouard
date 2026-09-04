CREATE DATABASE IF NOT EXISTS hotel_reservation;

USE hotel_reservation;

DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) UNIQUE NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) DEFAULT 'Available'
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(20) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    number_of_nights INT NOT NULL,
    total_cost DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) DEFAULT 'Reserved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (customer_id)
        REFERENCES customers(id),

    FOREIGN KEY (room_id)
        REFERENCES rooms(id)
);

INSERT INTO rooms (room_number, room_type, price_per_night, status) VALUES
('101', 'Single', 20000.00, 'Available'),
('102', 'Single', 20000.00, 'Available'),
('201', 'Double', 35000.00, 'Available'),
('202', 'Double', 35000.00, 'Available'),
('301', 'Suite', 60000.00, 'Available'),
('302', 'Suite', 60000.00, 'Available');

INSERT INTO users (full_name, email, password)
VALUES ('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');