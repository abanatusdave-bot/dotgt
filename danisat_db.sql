
USE danisat_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'customer') DEFAULT 'customer'
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    description TEXT,
    icon VARCHAR(50)
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    price DECIMAL(10,2),
    description TEXT,
    image VARCHAR(255),
    category VARCHAR(100),
    status ENUM('approved', 'pending') DEFAULT 'pending',
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100),
    message TEXT,
    rating INT,
    photo VARCHAR(255)
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    service VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);