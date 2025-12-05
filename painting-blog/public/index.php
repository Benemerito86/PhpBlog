<?php
/**
 * Front Controller - Punto de entrada de la aplicación
 */

// Iniciar sesión
session_start();

// Cargar configuración
require_once '../app/config/config.php';

// Cargar clases core
require_once '../app/core/Router.php';
require_once '../app/core/Controller.php';
require_once '../app/core/View.php';

// Cargar modelos
require_once '../app/models/Database.php';
require_once '../app/models/User.php';
require_once '../app/models/Post.php';
require_once '../app/models/Category.php';

// Inicializar y ejecutar el router
$router = new Router();
$router->dispatch();
