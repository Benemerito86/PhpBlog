-- Esquema de base de datos para el blog de pinturas
-- Ejecutar este archivo para crear las tablas necesarias

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS painting_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE painting_blog;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de publicaciones (pinturas)
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_category (category_id),
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar categorías por defecto
INSERT INTO categories (name, slug, description) VALUES
('Óleo', 'oleo', 'Pinturas al óleo sobre lienzo'),
('Acuarela', 'acuarela', 'Técnica de acuarela'),
('Acrílico', 'acrilico', 'Pinturas acrílicas'),
('Digital', 'digital', 'Arte digital y pinturas digitales'),
('Mixta', 'mixta', 'Técnicas mixtas'),
('Pastel', 'pastel', 'Pinturas al pastel');

-- Insertar usuario de prueba (contraseña: admin123)
-- La contraseña está hasheada con password_hash()
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@paintingblog.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Nota: Para crear tu propio usuario, usa el formulario de registro en la aplicación
-- o ejecuta en PHP: echo password_hash('tu_contraseña', PASSWORD_DEFAULT);
