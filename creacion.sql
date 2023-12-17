-- Create database
CREATE DATABASE IF NOT EXISTS YourDatabaseName;
USE YourDatabaseName;

-- Create table for user types
CREATE TABLE IF NOT EXISTS tipos_usuario (
    idTipo INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(255) NOT NULL
);

-- Insert default user types
INSERT INTO tipos_usuario (tipo) VALUES
('Admin'),
('Normal');

-- Create table for users
CREATE TABLE IF NOT EXISTS usuarios (
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    contrasenia VARCHAR(255) NOT NULL,
    tipo_usuario_id INT,
    FOREIGN KEY (tipo_usuario_id) REFERENCES tipos_usuario(idTipo)
);

-- Insert a sample admin user
INSERT INTO usuarios (nombre, contrasenia, tipo_usuario_id) VALUES
('AdminUser', '$2y$10$2aM1fsikw1KPXlW8bbatQuvj7LD.Y3Lt9mrvbCK5gUMaCSC9iqONK', 1);
