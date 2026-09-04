CREATE DATABASE hospital_system;

USE hospital_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'Staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id VARCHAR(20) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    emergency_contact VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE consultations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_name VARCHAR(100) NOT NULL,
    visit_date DATE NOT NULL,
    symptoms TEXT NOT NULL,
    diagnosis TEXT NOT NULL,
    treatment TEXT,
    prescription TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (patient_id)
    REFERENCES patients(id)
    ON DELETE CASCADE
);