-- Crear base de datos y usarla
CREATE DATABASE IF NOT EXISTS ServiciOs;
USE ServiciOs;

-- Tabla de Usuarios
CREATE TABLE Usuarios (
    ID_Usuarios INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50),
    Apellido VARCHAR(50),
    Correo VARCHAR(100) UNIQUE,
    ContrasenaHash VARCHAR(255),
    Verificado BOOLEAN DEFAULT FALSE,
    FechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_nombre_apellido ON Usuarios(Nombre, Apellido);

-- Tabla de Roles
CREATE TABLE Roles (
    ID_Rol INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50) UNIQUE
);

-- Tabla intermedia UsuarioRol
CREATE TABLE UsuarioRol (
    ID_Usuario INT,
    ID_Rol INT,
    PRIMARY KEY (ID_Usuario, ID_Rol),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Rol) REFERENCES Roles(ID_Rol)
);
CREATE INDEX idx_usuario ON UsuarioRol(ID_Usuario);
CREATE INDEX idx_rol ON UsuarioRol(ID_Rol);

-- Tabla de Categorías
CREATE TABLE Categorias (
    ID_Categoria INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50)
);
CREATE INDEX idx_categoria_nombre ON Categorias(Nombre);

-- Tabla de Subcategorías
CREATE TABLE Subcategorias (
    ID_Subcategoria INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50),
    ID_Categoria INT,
    FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria)
);
CREATE INDEX idx_subcategoria_categoria ON Subcategorias(ID_Categoria);

-- Tabla de Zonas geográficas de cobertura
CREATE TABLE Zonas (
    ID_Zona INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100)
);
CREATE INDEX idx_zona_nombre ON Zonas(Nombre);

-- Tabla de Servicios
CREATE TABLE Servicios (
    ID_Servicio INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100),
    Descripcion TEXT,
    Precio DECIMAL(10,2),
    ID_Categoria INT,
    ID_Subcategoria INT,
    ID_Usuario INT,
    ID_Zona INT,
    FechaPublicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria),
    FOREIGN KEY (ID_Subcategoria) REFERENCES Subcategorias(ID_Subcategoria),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Zona) REFERENCES Zonas(ID_Zona)
);
CREATE INDEX idx_servicio_categoria ON Servicios(ID_Categoria);
CREATE INDEX idx_servicio_subcategoria ON Servicios(ID_Subcategoria);
CREATE INDEX idx_servicio_usuario ON Servicios(ID_Usuario);
CREATE INDEX idx_servicio_zona ON Servicios(ID_Zona);

-- Tabla de Solicitudes de servicios
CREATE TABLE Solicita (
    ID_Solicitud INT PRIMARY KEY AUTO_INCREMENT,
    ID_Cliente INT,
    ID_Servicio INT,
    Estado ENUM('Pendiente', 'Aceptada', 'Rechazada', 'Completada') DEFAULT 'Pendiente',
    FechaSolicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Servicio) REFERENCES Servicios(ID_Servicio)
);
CREATE INDEX idx_solicitud_cliente ON Solicita(ID_Cliente);
CREATE INDEX idx_solicitud_servicio ON Solicita(ID_Servicio);
CREATE INDEX idx_solicitud_estado ON Solicita(Estado);

-- Tabla de Valoraciones
CREATE TABLE Valoraciones (
    ID_Valor INT PRIMARY KEY AUTO_INCREMENT,
    ID_Cliente INT,
    ID_Proveedor INT,
    Puntos INT CHECK (Puntos BETWEEN 1 AND 5),
    Comentario TEXT,
    FechaValoracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Proveedor) REFERENCES Usuarios(ID_Usuarios)
);
CREATE INDEX idx_valoracion_cliente ON Valoraciones(ID_Cliente);
CREATE INDEX idx_valoracion_proveedor ON Valoraciones(ID_Proveedor);

-- Tabla de Mensajes entre usuarios
CREATE TABLE Mensajes (
    ID_Mensaje INT PRIMARY KEY AUTO_INCREMENT,
    ID_Cliente INT,
    ID_Proveedor INT,
    Contenido TEXT,
    FechaHora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Proveedor) REFERENCES Usuarios(ID_Usuarios)
);
CREATE INDEX idx_mensaje_cliente ON Mensajes(ID_Cliente);
CREATE INDEX idx_mensaje_proveedor ON Mensajes(ID_Proveedor);

-- Tabla de Portafolio de trabajos realizados
CREATE TABLE Portafolio (
    ID_Portafolio INT PRIMARY KEY AUTO_INCREMENT,
    ID_Usuario INT,
    Titulo VARCHAR(100),
    Descripcion TEXT,
    Imagen VARCHAR(255),
    FechaSubida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuarios)
);
CREATE INDEX idx_portafolio_usuario ON Portafolio(ID_Usuario);


INSERT INTO Roles (Nombre) VALUES ('cliente');
INSERT INTO Roles (Nombre) VALUES ('proveedor');
INSERT INTO Roles (Nombre) VALUES ('admin');



