<?php
/**
 * Sistema de enrutamiento
 * Interpreta URLs amigables y las dirige al controlador correcto
 */
class Router
{
    private $routes = [];
    private $currentController = 'HomeController';
    private $currentMethod = 'index';
    private $params = [];

    public function __construct()
    {
        // Definir rutas
        $this->defineRoutes();
    }

    /**
     * Definir todas las rutas de la aplicación
     */
    private function defineRoutes()
    {
        // Rutas públicas
        $this->addRoute('GET', '', 'HomeController', 'index');
        $this->addRoute('GET', 'home', 'HomeController', 'index');
        $this->addRoute('GET', 'post/{slug}', 'PostController', 'show');
        $this->addRoute('GET', 'category/{id}', 'HomeController', 'category');

        // Rutas de autenticación
        $this->addRoute('GET', 'login', 'AuthController', 'login');
        $this->addRoute('POST', 'login', 'AuthController', 'authenticate');
        $this->addRoute('GET', 'register', 'AuthController', 'register');
        $this->addRoute('POST', 'register', 'AuthController', 'store');
        $this->addRoute('GET', 'logout', 'AuthController', 'logout');

        // Rutas de administración
        $this->addRoute('GET', 'admin', 'AdminController', 'dashboard');
        $this->addRoute('GET', 'admin/create', 'AdminController', 'create');
        $this->addRoute('POST', 'admin/create', 'AdminController', 'store');
        $this->addRoute('GET', 'admin/edit/{id}', 'AdminController', 'edit');
        $this->addRoute('POST', 'admin/update/{id}', 'AdminController', 'update');
        $this->addRoute('POST', 'admin/delete/{id}', 'AdminController', 'delete');
    }

    /**
     * Agregar una ruta
     */
    private function addRoute($method, $path, $controller, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Procesar la URL y ejecutar el controlador correspondiente
     */
    public function dispatch()
    {
        $url = $this->getUrl();
        $method = $_SERVER['REQUEST_METHOD'];

        // Buscar coincidencia en las rutas PRIMERO
        $routeFound = false;
        $isPublicRoute = in_array($url, ['login', 'register']);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertToRegex($route['path']);

            if (preg_match($pattern, $url, $matches)) {
                $routeFound = true;
                array_shift($matches);

                $this->currentController = $route['controller'];
                $this->currentMethod = $route['action'];
                $this->params = $matches;

                // Verificar autenticación SOLO si la ruta existe
                if (!isAuthenticated() && !$isPublicRoute) {
                    header('Location: ' . BASE_URL . '/login');
                    exit;
                }

                // Si está autenticado y trata de acceder a login, redirigir a home
                if (isAuthenticated() && $url === 'login') {
                    header('Location: ' . BASE_URL . '/');
                    exit;
                }

                return $this->executeController();
            }
        }

        // Si no hay coincidencia, mostrar 404
        if (!$routeFound) {
            $this->show404();
        }
    }

    /**
     * Convertir ruta a expresión regular
     */
    private function convertToRegex($path)
    {
        // Convertir {param} a expresión regular
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Obtener la URL limpia
     */
    private function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return $url;
        }
        return '';
    }

    /**
     * Ejecutar el controlador
     */
    private function executeController()
    {
        // Cargar el archivo del controlador
        $controllerFile = APP_PATH . '/controllers/' . $this->currentController . '.php';

        if (!file_exists($controllerFile)) {
            $this->show404();
            return;
        }

        require_once $controllerFile;

        // Instanciar el controlador
        $controller = new $this->currentController();

        // Verificar que el método existe
        if (!method_exists($controller, $this->currentMethod)) {
            $this->show404();
            return;
        }

        // Llamar al método con los parámetros
        call_user_func_array([$controller, $this->currentMethod], $this->params);
    }

    /**
     * Mostrar página 404
     */
    private function show404()
    {
        http_response_code(404);
        echo '<h1>404 - Página no encontrada</h1>';
        exit;
    }
}
