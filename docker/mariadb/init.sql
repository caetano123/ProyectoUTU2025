CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100),
    rol ENUM('cliente','proveedor','admin') DEFAULT 'cliente'
);

