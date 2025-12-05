-- Crear base de datos
CREATE DATABASE IF NOT EXISTS painting_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE painting_blog;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de posts (pinturas)
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt VARCHAR(300),
    image VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('draft', 'published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_category (category_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador (password: admin123)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@paintingblog.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insertar categorías
INSERT INTO categories (name, slug, description) VALUES
('Acrílica', 'acrilica', 'Pinturas realizadas con técnica acrílica'),
('Óleo', 'oleo', 'Pinturas al óleo sobre lienzo'),
('Acuarela', 'acuarela', 'Obras en acuarela sobre papel'),
('Digital', 'digital', 'Arte digital y pintura digital'),
('Pastel', 'pastel', 'Obras realizadas con pasteles');

-- Insertar pinturas de ejemplo
INSERT INTO posts (title, slug, content, excerpt, image, category_id, user_id, status) VALUES
('Atardecer en la Montaña', 'atardecer-en-la-montana', 'Una hermosa representación del atardecer sobre las montañas, capturando los tonos cálidos del cielo al caer la tarde. Esta obra fue creada usando técnicas de acrílico sobre lienzo de 60x80cm.', 'Atardecer vibrante con tonos cálidos sobre paisaje montañoso', 'atardecer-montana.jpg', 1, 1, 'published'),

('Retrato Abstracto', 'retrato-abstracto', 'Exploración de la forma humana a través de la abstracción. Utilizando óleo sobre lienzo, esta pieza juega con colores vibrantes y formas geométricas para representar la complejidad del ser humano.', 'Retrato abstracto en óleo con formas geométricas vibrantes', 'retrato-abstracto.jpg', 2, 1, 'published'),

('Jardín de Primavera', 'jardin-de-primavera', 'Acuarela delicada que captura la belleza efímera de un jardín en primavera. Los colores suaves y las transparencias características de la acuarela dan vida a flores y mariposas.', 'Acuarela delicada de jardín primaveral con flores', 'jardin-primavera.jpg', 3, 1, 'published'),

('Paisaje Urbano Nocturno', 'paisaje-urbano-nocturno', 'Arte digital que representa la vida nocturna de la ciudad. Luces de neón, reflejos en el asfalto mojado y la energía de la noche urbana capturados digitalmente.', 'Escena urbana nocturna con luces de neón digitales', 'urbano-nocturno.jpg', 4, 1, 'published'),

('Naturaleza Muerta', 'naturaleza-muerta', 'Composición clásica de naturaleza muerta realizada con pasteles. Frutas, flores y objetos cotidianos cobran vida con la textura única que proporcionan los pasteles.', 'Naturaleza muerta en pastel con frutas y flores', 'naturaleza-muerta.jpg', 5, 1, 'published'),

('Mar en Calma', 'mar-en-calma', 'Pintura acrílica que transmite la serenidad del mar en un día tranquilo. Los tonos azules y verdes se mezclan para crear una sensación de paz y contemplación.', 'Serenidad marina en tonos azules y verdes', 'mar-calma.jpg', 1, 1, 'published'),

('Bosque Otoñal', 'bosque-otonal', 'Óleo que captura los colores cálidos del otoño en un bosque. Naranjas, rojos y amarillos dominan esta escena natural llena de vida y cambio.', 'Bosque en otoño con colores cálidos vibrantes', 'bosque-otonal.jpg', 2, 1, 'published'),

('Flores Silvestres', 'flores-silvestres', 'Delicada acuarela de flores silvestres del campo. La transparencia del medio permite capturar la fragilidad y belleza de estas flores naturales.', 'Acuarela de flores silvestres del campo', 'flores-silvestres.jpg', 3, 1, 'published');
