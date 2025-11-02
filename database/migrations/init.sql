-- Crear base de datos y usarla
CREATE DATABASE IF NOT EXISTS ServiciOs;
USE ServiciOs;

-- Tabla de Usuarios y Administradores
CREATE TABLE Personas (
    ID_Persona INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50),
    Apellido VARCHAR(50),
    Correo VARCHAR(100) UNIQUE,
    Telefono VARCHAR(20),
    ContrasenaHash VARCHAR(255),
    Verificado BOOLEAN DEFAULT FALSE,
    FechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Tipo ENUM('ADMIN','USUARIO') NOT NULL DEFAULT 'USUARIO'
    );
CREATE INDEX idx_nombre_apellido ON Personas(Nombre, Apellido);

-- Tabla de Categorías
CREATE TABLE Categorias (
    ID_Categoria INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50)
);
CREATE INDEX idx_categoria_nombre ON Categorias(ID_Categoria);
-- Insertar Categorías predefinidas
INSERT INTO Categorias (ID_Categoria, Nombre) 
VALUES
(1, 'Diseño Gráfico y Creatividad'),
(2, 'Tecnología y Programación'),
(3, 'Marketing Digital y Ventas'),
(4, 'Video, Foto y Animación'),
(5, 'Negocios y Asistencia Virtual'),
(6, 'Hogar y Reparaciones'),
(7, 'Clases y Tutorías'),
(8, 'Eventos'),
(9, 'Cuidado y Bienestar'),
(10, 'Otros Servicios');

-- Tabla de Subcategorías
CREATE TABLE Subcategorias (
    ID_Subcategoria INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50),
    ID_Categoria INT,
    FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria)
);
CREATE INDEX idx_subcategoria_categoria ON Subcategorias(ID_Categoria);
INSERT INTO Subcategorias (ID_Subcategoria, Nombre, ID_Categoria) VALUES
-- Categoría 1: Diseño Gráfico y Creatividad
(1, 'Diseño de Logos e Identidad Visual', 1),
(2, 'Diseño Web y UI/UX', 1),
(3, 'Ilustración y Arte Digital', 1),
(4, 'Diseño para Redes Sociales', 1),
(5, 'Edición de Imágenes (Retoque)', 1),

-- Categoría 2: Tecnología y Programación
(6, 'Desarrollo Web (Full Stack)', 2),
(7, 'Desarrollo de Aplicaciones Móviles', 2),
(8, 'Soporte Técnico y Reparación de PC', 2),
(9, 'Desarrollo E-commerce', 2),
(10, 'Administración de Bases de Datos', 2),

-- Categoría 3: Marketing Digital y Ventas
(11, 'Manejo de Redes Sociales', 3),
(12, 'SEO (Optimización para Buscadores)', 3),
(13, 'Publicidad Paga (Google/Meta Ads)', 3),
(14, 'Redacción Publicitaria (Copywriting)', 3),
(15, 'Email Marketing y Automatización', 3),

-- Categoría 4: Video, Foto y Animación
(16, 'Edición de Video', 4),
(17, 'Fotografía de Eventos', 4),
(18, 'Fotografía de Producto', 4),
(19, 'Animación 2D y Motion Graphics', 4),
(20, 'Filmación y Producción de Video', 4),

-- Categoría 5: Negocios y Asistencia Virtual
(21, 'Asistencia Virtual (Tareas Administrativas)', 5),
(22, 'Carga de Datos (Data Entry)', 5),
(23, 'Contabilidad y Finanzas', 5),
(24, 'Consultoría de Negocios', 5),
(25, 'Gestión de Proyectos', 5),

-- Categoría 6: Hogar y Reparaciones
(26, 'Plomería (Sanitaria)', 6),
(27, 'Electricidad', 6),
(28, 'Carpintería (Armado de muebles)', 6),
(29, 'Pintura', 6),
(30, 'Jardinería y Mantenimiento', 6),

-- Categoría 7: Clases y Tutorías
(31, 'Clases de Idiomas (Inglés, etc.)', 7),
(32, 'Tutorías Escolares (Matemática, Física)', 7),
(33, 'Clases de Música (Guitarra, Piano)', 7),
(34, 'Entrenamiento Personalizado', 7),
(35, 'Preparación de Exámenes', 7),

-- Categoría 8: Eventos
(36, 'Organización de Eventos', 8),
(37, 'Servicios de Catering y Gastronomía', 8),
(38, 'DJ, Sonido e Iluminación', 8),
(39, 'Decoración y Ambientación', 8),
(40, 'Mozos y Bartenders', 8),

-- Categoría 9: Cuidado y Bienestar
(41, 'Peluquería y Estilismo', 9),
(42, 'Manicura y Pedicura', 9),
(43, 'Masajes Terapéuticos y Relajantes', 9),
(44, 'Cuidado de Mascotas (Paseos, Alojamiento)', 9),
(45, 'Cuidado de Adultos Mayores o Niños', 9),

(46, 'Otro Servicio', 10);

-- Tabla de Zonas geográficas de cobertura
CREATE TABLE Zonas (
    ID_Zona INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100)
);
CREATE INDEX idx_zona_nombre ON Zonas(Nombre);

-- Insertar Zonas predefinidas
INSERT INTO Zonas (Nombre) VALUES
('18 de Mayo'),   -- ID_Zona: 1
('Canelones'),    -- ID_Zona: 2
('La Paz'),       -- ID_Zona: 3
('Las Brujas'),   -- ID_Zona: 4
('Las Piedras'),  -- ID_Zona: 5
('Los Cerrillos'),-- ID_Zona: 6
('Montevideo'),   -- ID_Zona: 7
('Progreso'),     -- ID_Zona: 8
('Toledo');       -- ID_Zona: 9

-- Tabla de Servicios
CREATE TABLE Servicios (
    ID_Servicio INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100),
    Descripcion TEXT,
    Precio DECIMAL(10,2),
    ID_Categoria INT,
    ID_Subcategoria INT,
    ID_Persona INT,
    ID_Zona INT,
    FechaPublicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria),
    FOREIGN KEY (ID_Subcategoria) REFERENCES Subcategorias(ID_Subcategoria),
    FOREIGN KEY (ID_Persona) REFERENCES Personas(ID_Persona),
    FOREIGN KEY (ID_Zona) REFERENCES Zonas(ID_Zona)
);
CREATE INDEX idx_servicio_categoria ON Servicios(ID_Categoria);
CREATE INDEX idx_servicio_subcategoria ON Servicios(ID_Subcategoria);
CREATE INDEX idx_servicio_persona ON Servicios(ID_Persona);
CREATE INDEX idx_servicio_zona ON Servicios(ID_Zona);

-- Tabla de Solicitudes de servicios
CREATE TABLE Solicitudes (
    ID_Solicitud INT PRIMARY KEY AUTO_INCREMENT,
    ID_Persona INT,
    ID_Servicio INT,
    Estado ENUM('Pendiente', 'Aceptada', 'Rechazada', 'Completada') DEFAULT 'Pendiente',
    FechaSolicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Persona) REFERENCES Personas(ID_Persona),
    FOREIGN KEY (ID_Servicio) REFERENCES Servicios(ID_Servicio)
);
CREATE INDEX idx_solicitudes_persona ON Solicitudes(ID_Persona);
CREATE INDEX idx_solicitudes_servicio ON Solicitudes(ID_Servicio);
CREATE INDEX idx_solicitudes_estado ON Solicitudes(Estado);

-- Tabla de Valoraciones
CREATE TABLE Valoraciones (
    ID_Valor INT PRIMARY KEY AUTO_INCREMENT,
    ID_Cliente INT,
    ID_Proveedor INT,
    Puntos INT CHECK (Puntos BETWEEN 1 AND 5),
    Comentario TEXT,
    FechaValoracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Personas(ID_Persona),
    FOREIGN KEY (ID_Proveedor) REFERENCES Personas(ID_Persona)
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
    FOREIGN KEY (ID_Cliente) REFERENCES Personas(ID_Persona),
    FOREIGN KEY (ID_Proveedor) REFERENCES Personas(ID_Persona)
);
CREATE INDEX idx_mensaje_cliente ON Mensajes(ID_Cliente);
CREATE INDEX idx_mensaje_proveedor ON Mensajes(ID_Proveedor);

-- Tabla de Portafolio de trabajos realizados
CREATE TABLE Portafolio (
    ID_Portafolio INT PRIMARY KEY AUTO_INCREMENT,
    ID_Persona INT,
    Titulo VARCHAR(100),
    Descripcion TEXT,
    Imagen VARCHAR(255),
    FechaSubida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Persona) REFERENCES Personas(ID_Persona)
);
CREATE INDEX idx_portafolio_persona ON Portafolio(ID_Persona);

-- Tabla de Notificaciones
CREATE TABLE Notificaciones (
    ID_Notificacion INT PRIMARY KEY AUTO_INCREMENT,
    ID_Persona INT NOT NULL,
    Mensaje TEXT NOT NULL,
    URL VARCHAR(255) NOT NULL,
    Leida TINYINT(1) DEFAULT 0,
    FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (ID_Persona) REFERENCES Personas(ID_Persona) ON DELETE CASCADE
);

CREATE TABLE Posts ( 
    ID_Posts INT AUTO_INCREMENT PRIMARY KEY, 
    ID_Persona INT NOT NULL,
    ID_Categoria INT DEFAULT NULL,
    Titulo VARCHAR(255) NOT NULL,
    Contenido TEXT NOT NULL,
    FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FechaActualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Persona) REFERENCES Personas(ID_Persona),
    FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

