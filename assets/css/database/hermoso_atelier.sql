CREATE DATABASE IF NOT EXISTS hermoso_atelier
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE hermoso_atelier;

-- Customers and future staff/admin accounts
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    phone VARCHAR(30) NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Services shown on the atelier website
CREATE TABLE services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    description TEXT NULL,
    duration_minutes INT UNSIGNED NOT NULL DEFAULT 60,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO services (name, description, duration_minutes) VALUES
('Custom Tailoring', 'Bespoke designs tailored to measurements, style, and personality.', 90),
('Alterations and Repairs', 'Professional alterations and repairs for the perfect fit and finish.', 60),
('Fashion Consultation', 'Personalized style advice and design guidance.', 60),
('Bridal and Formal Wear', 'Exquisite bridal and evening wear for memorable moments.', 90),
('Ready to Wear Collection', 'Curated collections blending timeless elegance with modern sophistication.', 45);

-- Customer appointments
CREATE TABLE appointments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    service_id INT UNSIGNED NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    notes TEXT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_appointments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_appointments_service
        FOREIGN KEY (service_id) REFERENCES services(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    INDEX idx_appointment_schedule (appointment_date, appointment_time),
    INDEX idx_appointment_user (user_id)
) ENGINE=InnoDB;
