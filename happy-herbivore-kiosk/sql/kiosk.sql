
CREATE DATABASE IF NOT EXISTS kiosk;
USE kiosk;

CREATE TABLE products (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100),
price DECIMAL(6,2)
);

INSERT INTO products (name,price) VALUES
('Burger',8.50),
('Smoothie',4.50);
