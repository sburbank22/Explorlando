CREATE DATABASE ticket_store;

USE ticket_store;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100),
    email VARCHAR(150),
    phone VARCHAR(20),
    address VARCHAR(200),
    city VARCHAR(100),
    state VARCHAR(100),
    zipcode VARCHAR(10),
    ticket_qty INT,
    total_price DECIMAL(10,2),
    card_number VARCHAR(20),
    expiry VARCHAR(10),
    cvv VARCHAR(5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);