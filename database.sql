SET time_zone = "+07:00";

CREATE DATABASE IF NOT EXISTS UserManagement;

USE UserManagement;

CREATE TABLE IF NOT EXISTS roles
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    role_name  VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    username       VARCHAR(50)  NOT NULL UNIQUE,
    password       VARCHAR(255) NOT NULL,
    email          VARCHAR(100) NOT NULL UNIQUE,
    full_name      VARCHAR(100),
    phone_number   VARCHAR(15),
    address        VARCHAR(255),
    gender         ENUM ('Male', 'Female', 'Other'),
    loyalty_points INT                   DEFAULT 0,
    role_id        INT          NOT NULL DEFAULT 2,
    type_id        INT                   DEFAULT 2,
    is_active      INT          NOT NULL DEFAULT 1,
    created_at     TIMESTAMP             DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP             DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles (id)
);

CREATE TABLE IF NOT EXISTS type_customers
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    type_name  VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS feedbacks
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100),
    email      VARCHAR(100),
    phone      VARCHAR(15),
    message    TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read    INT       default 0
);

INSERT INTO roles (role_name)
VALUES ('admin'),
       ('customer');

INSERT INTO type_customers (type_name)
VALUES ('VIP'),
       ('Normal');

INSERT INTO users (username, password, email, full_name, phone_number, address, gender, loyalty_points, role_id,
                   type_id)
VALUES ('admin', '$2y$10$ofnVZF5BOVbwt2uCR2EwdObZPxgJjD04HGXAWInqpsFvLlR/kNsGu',
        'admin@example.com', 'Nguyễn Văn A', '0123456789', '123 Đường ABC, Hà Nội', 'Male', 100, 1, NULL),

       ('vip_user1', '$2y$10$ofnVZF5BOVbwt2uCR2EwdObZPxgJjD04HGXAWInqpsFvLlR/kNsGu',
        'vip1@example.com', 'Trần Thị B', '0123456781', '456 Đường XYZ, TP.HCM', 'Female', 200, 2, 1),

       ('vip_user2', '$2y$10$ofnVZF5BOVbwt2uCR2EwdObZPxgJjD04HGXAWInqpsFvLlR/kNsGu',
        'vip2@example.com', 'Lê Minh C', '0123456782', '789 Đường DEF, Đà Nẵng', 'Male', 150, 2, 1),

       ('user1', '$2y$10$ofnVZF5BOVbwt2uCR2EwdObZPxgJjD04HGXAWInqpsFvLlR/kNsGu',
        'user1@example.com', 'Nguyễn Thị L', '0987654321', '321 Đường PQR, Hà Nội', 'Female', 50, 2, 2),

       ('user2', '$2y$10$ofnVZF5BOVbwt2uCR2EwdObZPxgJjD04HGXAWInqpsFvLlR/kNsGu',
        'user2@example.com', 'Trần Minh M', '0987654322', '654 Đường GHI, TP.HCM', 'Male', 30, 2, 2),
       ('user3', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user3@example.com', 'Nguyễn Văn B', '09876543211', '123 Đường M, Hà Nội', 'Male', 20, 2, 2),

       ('user4', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user4@example.com', 'Phạm Thị C', '09876543212', '456 Đường N, TP.HCM', 'Female', 35, 2, 2),

       ('user5', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user5@example.com', 'Trần Văn D', '09876543213', '789 Đường O, Đà Nẵng', 'Male', 50, 2, 2),

       ('user6', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user6@example.com', 'Lê Thị E', '09876543214', '321 Đường P, Hà Nội', 'Female', 75, 2, 1),

       ('user7', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user7@example.com', 'Nguyễn Minh F', '09876543215', '654 Đường Q, TP.HCM', 'Male', 120, 2, 1),

       ('user8', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user8@example.com', 'Phạm Văn G', '09876543216', '987 Đường R, Đà Nẵng', 'Male', 90, 2, 2),

       ('user9', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user9@example.com', 'Trần Thị H', '09876543217', '123 Đường S, Hà Nội', 'Female', 200, 2, 2),

       ('user10', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user10@example.com', 'Lê Văn I', '09876543218', '456 Đường T, TP.HCM', 'Male', 55, 2, 1),

       ('user11', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user11@example.com', 'Nguyễn Thị J', '09876543219', '789 Đường U, Đà Nẵng', 'Female', 40, 2, 1),

       ('user12', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user12@example.com', 'Phạm Văn K', '09876543220', '321 Đường V, Hà Nội', 'Male', 85, 2, 2),

       ('user13', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user13@example.com', 'Trần Thị L', '09876543221', '654 Đường W, TP.HCM', 'Female', 95, 2, 2),

       ('user14', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user14@example.com', 'Lê Văn M', '09876543222', '987 Đường X, Đà Nẵng', 'Male', 110, 2, 1),

       ('user15', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user15@example.com', 'Nguyễn Thị N', '09876543223', '123 Đường Y, Hà Nội', 'Female', 130, 2, 2),

       ('user16', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user16@example.com', 'Phạm Văn O', '09876543224', '456 Đường Z, TP.HCM', 'Male', 160, 2, 2),

       ('user17', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user17@example.com', 'Trần Thị P', '09876543225', '789 Đường AA, Đà Nẵng', 'Female', 70, 2, 1),

       ('user18', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user18@example.com', 'Lê Văn Q', '09876543226', '321 Đường BB, Hà Nội', 'Male', 45, 2, 2),

       ('user19', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user19@example.com', 'Nguyễn Minh R', '09876543227', '654 Đường CC, TP.HCM', 'Male', 60, 2, 1),

       ('user20', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user20@example.com', 'Phạm Thị S', '09876543228', '987 Đường DD, Đà Nẵng', 'Female', 30, 2, 2),

       ('user21', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user21@example.com', 'Trần Văn T', '09876543229', '123 Đường EE, Hà Nội', 'Male', 100, 2, 2),

       ('user22', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12',
        'user22@example.com', 'Lê Thị U', '09876543230', '456 Đường FF, TP.HCM', 'Female', 180, 2, 1);
