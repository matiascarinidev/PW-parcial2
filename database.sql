CREATE DATABASE IF NOT EXISTS parcial2;

USE parcial2;

CREATE TABLE
    IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE
    );

INSERT INTO
    usuarios (usuario, password, email)
VALUES
    ('admin', '123456', 'admin@test.com'),
    ('user1', '123456', 'user1@test.com'),
    ('user2', '123456', 'user2@test.com'),
    ('user3', '123456', 'user3@test.com'),
    ('user4', '123456', 'user4@test.com'),
    ('user5', '123456', 'user5@test.com');