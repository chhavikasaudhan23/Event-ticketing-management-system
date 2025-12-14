-- Create DB and tables;

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150),
  email VARCHAR(150) UNIQUE,
  password VARCHAR(255),
  phone VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS admins (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150),
  email VARCHAR(150) UNIQUE,
  password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS events (
  event_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200),
  description TEXT,
  date DATE,
  time TIME,
  venue VARCHAR(255),
  category VARCHAR(100),
  price DECIMAL(10,2),
  total_seats INT DEFAULT 0,
  available_seats INT DEFAULT 0,
  image_path VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  event_id INT,
  quantity INT,
  amount_paid DECIMAL(10,2),
  booking_date DATETIME,
  payment_status VARCHAR(50),
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
  FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE SET NULL
);
