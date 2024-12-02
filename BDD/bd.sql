CREATE DATABASE ForoDB;

USE ForoDB;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    nombre_real VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL -- Almacenada con BCRYPT
    
);

-- Tabla de preguntas
CREATE TABLE preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de respuestas
CREATE TABLE respuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contenido VARCHAR(500) NOT NULL,
    usuario_id INT NOT NULL,
    pregunta_id INT NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
);

-- Insertar usuarios
INSERT INTO usuarios (nombre_usuario, nombre_real, email, contrasena) VALUES
('usuario1', 'Juan Pérez', 'juan.perez@example.com', 'hashed_password1'),
('usuario2', 'María López', 'maria.lopez@example.com', 'hashed_password2'),
('usuario3', 'Carlos García', 'carlos.garcia@example.com', 'hashed_password3');

-- Insertar preguntas
INSERT INTO preguntas (titulo, descripcion, usuario_id) VALUES
('¿Cómo instalar MySQL?', 'Estoy teniendo problemas para instalar MySQL en mi computadora. ¿Alguien puede ayudarme?', 1),
('¿Qué es una clave primaria?', 'No entiendo bien el concepto de clave primaria en bases de datos. ¿Podrían explicármelo?', 2),
('¿Cómo hacer un JOIN en SQL?', 'Necesito unir dos tablas en mi consulta SQL. ¿Cómo se hace un JOIN?', 3);

-- Insertar respuestas
INSERT INTO respuestas (contenido, usuario_id, pregunta_id) VALUES
('Puedes descargar el instalador desde el sitio oficial de MySQL.', 2, 1),
('Una clave primaria es un campo que identifica de manera única cada registro en una tabla.', 3, 2),
('Para hacer un JOIN, puedes usar la cláusula JOIN seguida del tipo de JOIN que necesitas.', 1, 3);