CREATE DATABASE IF NOT EXISTS YourDatabaseName;
USE YourDatabaseName;

CREATE TABLE IF NOT EXISTS tipos_usuario (
    idTipo INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(255) NOT NULL
);

INSERT INTO tipos_usuario (tipo) VALUES
('Admin'),
('Normal');

CREATE TABLE IF NOT EXISTS usuarios (
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    contrasenia VARCHAR(255) NOT NULL,
    tipo_usuario_id INT,
    FOREIGN KEY (tipo_usuario_id) REFERENCES tipos_usuario(idTipo)
);
