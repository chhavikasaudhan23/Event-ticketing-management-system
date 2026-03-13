CREATE DATABASE event_ticketing_db;
USE event_ticketing_db;

CREATE TABLE admins (
 admin_id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100),
 email VARCHAR(100),
 password VARCHAR(255)
);

INSERT INTO admins VALUES (1,'Admin','admin@gmail.com',MD5('admin123'));

CREATE TABLE users (
 user_id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100),
 email VARCHAR(100),
 password VARCHAR(255)
);

CREATE TABLE events (
 event_id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(200),
 event_date DATE,
 event_time TIME,
 location VARCHAR(200),
 price INT,
 total_tickets INT,
 available_tickets INT
);

CREATE TABLE bookings (
 booking_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 event_id INT,
 tickets INT,
 payment_status VARCHAR(20),
 booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
