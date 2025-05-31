CREATE DATABASE IF NOT EXISTS ServiciOs;
USE ServiciOs;

CREATE TABLE IF NOT EXISTS Usuarios (
    CI VARCHAR(15) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Apellido VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Telefono VARCHAR(20),
    Direccion VARCHAR(255),
    Contraseña VARCHAR(100) NOT NULL,
    Tipo ENUM('Cliente', 'Proveedor', 'Ambos') NOT NULL
);

CREATE TABLE IF NOT EXISTS Categorias (
    ID_categoria INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS Servicios (
    ID_servicio INT PRIMARY KEY AUTO_INCREMENT,
    CI_proveedor VARCHAR(15) NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    Descripcion TEXT,
    ID_categoria INT NOT NULL,
    FOREIGN KEY (CI_proveedor) REFERENCES Usuarios(CI),
    FOREIGN KEY (ID_categoria) REFERENCES Categorias(ID_categoria)
);

CREATE TABLE IF NOT EXISTS Solicitudes (
    ID_solicitud INT PRIMARY KEY AUTO_INCREMENT,
    CI_cliente VARCHAR(15) NOT NULL,
    ID_servicio INT NOT NULL,
    Fecha_solicitud DATE NOT NULL,
    Estado ENUM('Pendiente', 'Aceptada', 'Completada', 'Cancelada') NOT NULL,
    FOREIGN KEY (CI_cliente) REFERENCES Usuarios(CI),
    FOREIGN KEY (ID_servicio) REFERENCES Servicios(ID_servicio)
);

CREATE TABLE IF NOT EXISTS Valoraciones (
    ID_valor INT PRIMARY KEY AUTO_INCREMENT,
    ID_solicitud INT NOT NULL,
    CI_cliente VARCHAR(15) NOT NULL,
    CI_proveedor VARCHAR(15) NOT NULL,
    Puntuacion INT CHECK (Puntuacion BETWEEN 1 AND 5),
    Comentario TEXT,
    FOREIGN KEY (ID_solicitud) REFERENCES Solicitudes(ID_solicitud),
    FOREIGN KEY (CI_cliente) REFERENCES Usuarios(CI),
    FOREIGN KEY (CI_proveedor) REFERENCES Usuarios(CI)
);
