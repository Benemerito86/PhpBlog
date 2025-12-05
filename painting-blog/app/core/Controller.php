<?php
/**
 * Clase base para todos los controladores
 */
class Controller
{
    /**
     * Cargar un modelo
     */
    protected function model($model)
    {
        $modelFile = APP_PATH . '/models/' . $model . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }

        die("El modelo {$model} no existe");
    }

    /**
     * Cargar una vista
     */
    protected function view($view, $data = [])
    {
        $viewFile = APP_PATH . '/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Extraer datos para usar en la vista
            extract($data);
            require_once $viewFile;
        } else {
            die("La vista {$view} no existe");
        }
    }

    /**
     * Redirigir a otra URL
     */
    protected function redirect($url)
    {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }

    /**
     * Verificar si el usuario está autenticado
     */
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Requerir autenticación (middleware)
     */
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $_SESSION['error'] = 'Debes iniciar sesión para acceder a esta página';
            $this->redirect('login');
        }
    }

    /**
     * Obtener el usuario actual
     */
    protected function currentUser()
    {
        if ($this->isAuthenticated()) {
            $userModel = $this->model('User');
            return $userModel->findById($_SESSION['user_id']);
        }
        return null;
    }

    /**
     * Establecer mensaje flash
     */
    protected function setFlash($type, $message)
    {
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Obtener y limpiar mensaje flash
     */
    protected function getFlash($type)
    {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    /**
     * Retornar JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
