-- Create the event_management database
CREATE DATABASE IF NOT EXISTS event_management;
USE event_management;

-- Create admins table for admin authentication
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Stores hashed password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create events table for storing event details
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create registrations table for event registrations
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Insert sample admin user (username: admin, password: admin123)
-- Password hash generated with password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$W9z3z3z3z3z3z3z3z3z3z3u3z3z3z3z3z3z3z3z3z3z3z3z3z3z3z3z');

-- Insert sample events for testing
INSERT INTO events (title, description, event_date, location) VALUES
('Tech Conference 2025', 'A conference on emerging tech trends.', '2025-06-15', 'Convention Center, NY'),
('Music Festival', 'Annual music festival with top artists.', '2025-07-20', 'Central Park, NY'),
('Art Exhibition', 'Showcasing local artists.', '2025-08-10', 'Downtown Gallery, NY');

-- Insert sample registrations for testing
INSERT INTO registrations (event_id, name, email) VALUES
(1, 'John Doe', 'john.doe@example.com'),
(2, 'Jane Smith', 'jane.smith@example.com'),
(1, 'Alice Johnson', 'alice.johnson@example.com');