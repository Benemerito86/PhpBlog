<?php
require_once '../app/core/Controller.php';
require_once '../app/helpers/validation.php';

/**
 * AuthController - Controlador de autenticación
 */
class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    /**
     * Mostrar formulario de login
     */
    public function login()
    {
        $data = [
            'title' => 'Iniciar Sesión'
        ];

        $this->view('auth/login', $data);
    }

    /**
     * Procesar autenticación
     */
    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $data = [
                'title' => 'Iniciar Sesión',
                'error' => 'Por favor, completa todos los campos'
            ];
            $this->view('auth/login', $data);
            return;
        }

        // Buscar usuario
        $user = $this->userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header('Location: ' . BASE_URL . '/');
            exit;
        } else {
            $data = [
                'title' => 'Iniciar Sesión',
                'error' => 'Usuario o contraseña incorrectos'
            ];
            $this->view('auth/login', $data);
        }
    }

    /**
     * Mostrar formulario de registro
     */
    public function register()
    {
        // Si ya está autenticado, redirigir al admin
        if ($this->isAuthenticated()) {
            $this->redirect('admin');
            return;
        }

        $data = [
            'title' => 'Registrarse'
        ];

        $this->view('auth/register', $data);
    }

    /**
     * Procesar registro
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('register');
            return;
        }

        // Sanitizar datos
        $username = sanitizeString($_POST['username'] ?? '');
        $email = sanitizeString($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validar datos
        $errors = validateFormData($_POST, [
            'username' => ['required' => true, 'username' => true],
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true, 'password' => true]
        ]);

        // Validar que las contraseñas coincidan
        if ($password !== $confirmPassword) {
            $errors['confirm_password'][] = 'Las contraseñas no coinciden';
        }

        if (!empty($errors)) {
            $errorMessages = [];
            foreach ($errors as $fieldErrors) {
                $errorMessages = array_merge($errorMessages, $fieldErrors);
            }
            $this->setFlash('error', implode('<br>', $errorMessages));
            $this->redirect('register');
            return;
        }

        // Crear usuario
        $result = $this->userModel->create($username, $email, $password);

        if ($result['success']) {
            $this->setFlash('success', 'Cuenta creada exitosamente. Por favor, inicia sesión.');
            $this->redirect('login');
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('register');
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
