-- Script SQL para crear la base de datos de la tienda de ropa elegante
CREATE DATABASE IF NOT EXISTS tienda_ropa_elegante;
USE tienda_ropa_elegante;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de productos (para futuras funcionalidades)
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(100),
    talla VARCHAR(10),
    color VARCHAR(50),
    stock INT DEFAULT 0,
    imagen VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar algunos productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, categoria, talla, color, stock, imagen) VALUES
('Vestido Elegante Negro', 'Vestido de noche elegante, perfecto para ocasiones especiales', 299.99, 'Vestidos', 'M', 'Negro', 15, 'vestido-negro.jpg'),
('Camisa Formal Blanca', 'Camisa formal de algodón premium para hombres', 89.99, 'Camisas', 'L', 'Blanco', 25, 'camisa-blanca.jpg'),
('Pantalón de Vestir', 'Pantalón de vestir clásico para ocasiones formales', 149.99, 'Pantalones', 'M', 'Azul Marino', 20, 'pantalon-vestir.jpg'),
('Blazer Ejecutivo', 'Blazer elegante para look profesional', 199.99, 'Blazers', 'L', 'Gris', 12, 'blazer-gris.jpg');

-- Insertar usuario de prueba (password: 123456)
INSERT INTO usuarios (nombre, email, password, telefono, direccion) VALUES
('Usuario Demo', 'demo@tienda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '555-0123', 'Calle Principal 123, Ciudad');
