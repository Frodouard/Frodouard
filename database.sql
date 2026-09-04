CREATE DATABASE IF NOT EXISTS employee_payroll;
USE employee_payroll;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(30) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30),
    email VARCHAR(100),
    department_id INT,
    position VARCHAR(100),
    employment_date DATE,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (department_id)
    REFERENCES departments(id)
    ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS payroll (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    payroll_month VARCHAR(7) NOT NULL,
    basic_salary DECIMAL(12,2) DEFAULT 0,
    allowances DECIMAL(12,2) DEFAULT 0,
    bonus DECIMAL(12,2) DEFAULT 0,
    deductions DECIMAL(12,2) DEFAULT 0,
    gross_salary DECIMAL(12,2) DEFAULT 0,
    net_salary DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (employee_id)
    REFERENCES employees(id)
    ON DELETE CASCADE,

    UNIQUE KEY uq_payroll_employee_month
    (employee_id, payroll_month)
);

INSERT IGNORE INTO departments (department_name)
VALUES
('IT'),
('Finance'),
('Human Resources'),
('Marketing'),
('Administration');