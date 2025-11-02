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




-- Crear usuario root/admin al inicializar la base de datos
INSERT INTO Personas (Nombre, Apellido, Correo, Telefono, ContrasenaHash, Verificado, Tipo)
VALUES (
    'Root',
    'Admin',
    'root@servicios.local',
    '000000000',
    '$2y$10$wC7ZazK4Z9oQuhnW.UB3mud7mWcAG11PEVsh2U.GG.Sbj8MLvVGfO', -- hash de 1234567
    TRUE,
    'ADMIN'
)
ON DUPLICATE KEY UPDATE ID_Persona=ID_Persona;



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

-- Tabla para tokens de recuperación de contraseña
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    CI INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    used TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (CI) REFERENCES Personas(ID_Persona) ON DELETE CASCADE
);
CREATE INDEX idx_password_reset_ci ON password_resets(CI);
CREATE INDEX idx_password_reset_token ON password_resets(token_hash);

-- ====================================
-- Datos de prueba para Personas
-- ====================================
INSERT INTO Personas (Nombre, Apellido, Correo, Telefono, ContrasenaHash, Verificado, Tipo)
VALUES
('Juan', 'Pérez', 'juan.perez@test.com', '099111111', '$2y$10$wC7ZazK4Z9oQuhnW.UB3mud7mWcAG11PEVsh2U.GG.Sbj8MLvVGfO', TRUE, 'USUARIO'),
('María', 'González', 'maria.gonzalez@test.com', '099222222', '$2y$10$wC7ZazK4Z9oQuhnW.UB3mud7mWcAG11PEVsh2U.GG.Sbj8MLvVGfO', TRUE, 'USUARIO'),
('Carlos', 'Rodríguez', 'carlos.rodriguez@test.com', '099333333', '$2y$10$wC7ZazK4Z9oQuhnW.UB3mud7mWcAG11PEVsh2U.GG.Sbj8MLvVGfO', TRUE, 'USUARIO'),
('Lucía', 'Fernández', 'lucia.fernandez@test.com', '099444444', '$2y$10$wC7ZazK4Z9oQuhnW.UB3mud7mWcAG11PEVsh2U.GG.Sbj8MLvVGfO', TRUE, 'USUARIO'),
('Andrés', 'Santos', 'andres.santos@test.com', '099555555', '$2y$10$wC7ZazK4Z9oQuhnW.UB3mud7mWcAG11PEVsh2U.GG.Sbj8MLvVGfO', TRUE, 'USUARIO');

-- ====================================
-- Datos de prueba para Subcategorías
-- ====================================
INSERT INTO Subcategorias (Nombre, ID_Categoria)
VALUES
('Diseño de Logos',1),
('Diseño Web',1),
('Desarrollo Web',2),
('Aplicaciones Móviles',2),
('SEO',3),
('Marketing en Redes',3),
('Fotografía',4),
('Video Corporativo',4),
('Asistencia Virtual',5),
('Reparaciones Hogar',6);

-- ====================================
-- Datos de prueba para Servicios
-- ====================================
INSERT INTO Servicios (Nombre, Descripcion, Precio, ID_Categoria, ID_Subcategoria, ID_Persona, ID_Zona)
VALUES
('Diseño de Logo Profesional', 'Logo creativo para tu empresa', 50.00, 1, 1, 2, 5),
('Sitio Web Corporativo', 'Desarrollo de sitio web completo', 300.00, 2, 3, 3, 7),
('Campaña de SEO', 'Optimización SEO para tu web', 150.00, 3, 5, 4, 2),
('Fotografía de Producto', 'Fotografía profesional de tus productos', 80.00, 4, 7, 5, 1),
('Asistente Virtual', 'Gestión de emails y agenda', 100.00, 5, 9, 2, 3);

-- ====================================
-- Datos de prueba para Solicitudes
-- ====================================
INSERT INTO Solicitudes (ID_Persona, ID_Servicio, Estado)
VALUES
(1,1,'Pendiente'),
(3,2,'Aceptada'),
(5,3,'Rechazada'),
(4,4,'Completada'),
(2,5,'Pendiente');

-- ====================================
-- Datos de prueba para Valoraciones
-- ====================================
INSERT INTO Valoraciones (ID_Cliente, ID_Proveedor, Puntos, Comentario)
VALUES
(1,2,5,'Excelente servicio!'),
(3,3,4,'Buen trabajo, pero tardó un poco'),
(4,5,3,'Aceptable, podría mejorar'),
(2,2,5,'Muy profesional');

-- ====================================
-- Datos de prueba para Mensajes
-- ====================================
INSERT INTO Mensajes (ID_Cliente, ID_Proveedor, Contenido)
VALUES
(1,2,'Hola, me interesa tu servicio.'),
(3,3,'¿Podrías enviarme más información?'),
(4,5,'Gracias por tu ayuda.'),
(2,2,'Cuando podemos empezar?');

-- ====================================
-- Datos de prueba para Portafolio
-- ====================================
INSERT INTO Portafolio (ID_Persona, Titulo, Descripcion, Imagen)
VALUES
(2,'Logo Empresa X','Logo diseñado para Empresa X','logo1.jpg'),
(3,'Sitio Web Y','Sitio web desarrollado para Y','web1.jpg'),
(4,'Campaña SEO Z','Optimización de SEO','seo1.jpg');

-- ====================================
-- Datos de prueba para Notificaciones
-- ====================================
INSERT INTO Notificaciones (ID_Persona, Mensaje, URL)
VALUES
(1,'Tu solicitud ha sido aceptada','/solicitudes/1'),
(2,'Nuevo mensaje de un cliente','/mensajes'),
(3,'Tu servicio fue valorado','/valoraciones/2');

-- ====================================
-- Datos de prueba para Posts
-- ====================================
INSERT INTO Posts (ID_Persona, ID_Categoria, Titulo, Contenido)
VALUES
(2,1,'Cómo diseñar un logo profesional','Aquí te enseñamos a diseñar logos profesionales.'),
(3,2,'Mejores prácticas en desarrollo web','Consejos para desarrollo web moderno.'),
(4,3,'Tips de marketing digital','Estrategias efectivas para redes sociales.');

