<?php
/**
 * Motor de plantillas simple
 * Gestiona la renderización de vistas con layouts
 */
class View
{
    private $layout = 'layouts/main';
    private $data = [];

    /**
     * Establecer el layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Renderizar una vista con layout
     */
    public function render($view, $data = [])
    {
        $this->data = $data;
        extract($data);

        // Capturar el contenido de la vista
        ob_start();
        $viewFile = APP_PATH . '/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("La vista {$view} no existe");
        }

        $content = ob_get_clean();

        // Renderizar con layout
        if ($this->layout) {
            $layoutFile = APP_PATH . '/views/' . $this->layout . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * Escapar HTML para prevenir XSS
     */
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
