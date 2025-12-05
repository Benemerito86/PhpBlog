<?php
/**
 * Configuración de la aplicación
 */

// Rutas de la aplicación (definir primero)
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Configuración de base de datos - SQLite (compatible con MySQL)
define('DB_TYPE', 'sqlite');
define('DB_PATH', ROOT_PATH . '/database/painting_blog.sqlite');

// URL base (ajustar según tu configuración)
define('BASE_URL', 'http://localhost:8000');
define('UPLOAD_URL', BASE_URL . '/uploads');

// Configuración de sesión
define('SESSION_NAME', 'painting_blog_session');

// Configuración de subida de archivos
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_MIME_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Configuración de la aplicación
define('APP_NAME', 'Galería de Pinturas');
define('APP_DESCRIPTION', 'Blog profesional de pinturas y arte');

// Zona horaria
date_default_timezone_set('Europe/Madrid');

// Mostrar errores en desarrollo (cambiar a false en producción)
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ============================================
// FUNCIONES HELPER
// ============================================

/**
 * Escapar HTML para prevenir XSS
 */
function escapeHtml($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirigir a una URL
 */
function redirect($path = '')
{
    header('Location: ' . BASE_URL . '/' . $path);
    exit;
}

/**
 * Verificar si el usuario está autenticado
 */
function isAuthenticated()
{
    return isset($_SESSION['user_id']);
}

/**
 * Obtener el usuario actual
 */
function currentUser()
{
    return $_SESSION['user'] ?? null;
}
