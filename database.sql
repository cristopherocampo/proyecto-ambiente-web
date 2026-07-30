CREATE DATABASE IF NOT EXISTS bookcycle CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bookcycle;

-- Tablas Catálogo
CREATE TABLE IF NOT EXISTS instituciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS carreras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla Principal de Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    institucion_id INT,
    carrera_id INT,
    creditos INT DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (institucion_id) REFERENCES instituciones(id) ON DELETE SET NULL,
    FOREIGN KEY (carrera_id) REFERENCES carreras(id) ON DELETE SET NULL
);

-- Registros Iniciales
INSERT INTO instituciones (nombre) VALUES 
('Universidad Fidélitas'), ('Universidad de Costa Rica'), ('TEC');

INSERT INTO carreras (nombre) VALUES 
('Ingeniería en Sistemas'), ('Administración de Empresas'), ('Derecho');