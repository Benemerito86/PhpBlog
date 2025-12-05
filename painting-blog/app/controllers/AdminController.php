<?php
require_once '../app/core/Controller.php';
require_once '../app/helpers/validation.php';
require_once '../app/helpers/upload.php';

/**
 * AdminController - Controlador del panel de administración
 */
class AdminController extends Controller
{
    private $postModel;
    private $categoryModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
        $this->categoryModel = $this->model('Category');
    }

    /**
     * Dashboard de administración
     */
    public function dashboard()
    {
        $this->requireAuth();

        $posts = $this->postModel->findAll();
        $categories = $this->categoryModel->findAll();
        $user = $this->currentUser();

        $data = [
            'title' => 'Panel de Administración',
            'posts' => $posts,
            'categories' => $categories,
            'user' => $user,
            'totalPosts' => count($posts)
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Mostrar formulario de crear publicación
     */
    public function create()
    {
        $this->requireAuth();

        $categories = $this->categoryModel->findAll();

        $data = [
            'title' => 'Nueva Publicación',
            'categories' => $categories
        ];

        $this->view('admin/create', $data);
    }

    /**
     * Guardar nueva publicación
     */
    public function store()
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/create');
            return;
        }

        // Sanitizar datos
        $title = sanitizeString($_POST['title'] ?? '');
        $description = sanitizeString($_POST['description'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);

        // Validar datos
        $errors = [];

        if (empty($title)) {
            $errors[] = 'El título es requerido';
        }

        if (empty($description)) {
            $errors[] = 'La descripción es requerida';
        }

        if ($categoryId <= 0) {
            $errors[] = 'Debes seleccionar una categoría';
        }

        // Validar imagen
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Debes seleccionar una imagen';
        } else {
            $imageErrors = validateImage($_FILES['image']);
            $errors = array_merge($errors, $imageErrors);
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/create');
            return;
        }

        // Subir imagen
        $uploadResult = uploadImage($_FILES['image']);

        if (!$uploadResult['success']) {
            $this->setFlash('error', $uploadResult['message']);
            $this->redirect('admin/create');
            return;
        }

        // Crear publicación
        $postData = [
            'title' => $title,
            'description' => $description,
            'image_path' => $uploadResult['filename'],
            'category_id' => $categoryId,
            'user_id' => $_SESSION['user_id']
        ];

        $result = $this->postModel->create($postData);

        if ($result['success']) {
            $this->setFlash('success', 'Publicación creada exitosamente');
            $this->redirect('admin');
        } else {
            // Eliminar imagen si falló la creación
            deleteFile($uploadResult['filename']);
            $this->setFlash('error', $result['message']);
            $this->redirect('admin/create');
        }
    }

    /**
     * Mostrar formulario de editar publicación
     */
    public function edit($id)
    {
        $this->requireAuth();

        $post = $this->postModel->findById($id);

        if (!$post) {
            $this->setFlash('error', 'Publicación no encontrada');
            $this->redirect('admin');
            return;
        }

        $categories = $this->categoryModel->findAll();

        $data = [
            'title' => 'Editar Publicación',
            'post' => $post,
            'categories' => $categories
        ];

        $this->view('admin/edit', $data);
    }

    /**
     * Actualizar publicación
     */
    public function update($id)
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/edit/' . $id);
            return;
        }

        $post = $this->postModel->findById($id);

        if (!$post) {
            $this->setFlash('error', 'Publicación no encontrada');
            $this->redirect('admin');
            return;
        }

        // Sanitizar datos
        $title = sanitizeString($_POST['title'] ?? '');
        $description = sanitizeString($_POST['description'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);

        // Validar datos
        $errors = [];

        if (empty($title)) {
            $errors[] = 'El título es requerido';
        }

        if (empty($description)) {
            $errors[] = 'La descripción es requerida';
        }

        if ($categoryId <= 0) {
            $errors[] = 'Debes seleccionar una categoría';
        }

        // Validar imagen si se subió una nueva
        $newImageFilename = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $imageErrors = validateImage($_FILES['image']);

            if (!empty($imageErrors)) {
                $errors = array_merge($errors, $imageErrors);
            } else {
                // Subir nueva imagen
                $uploadResult = uploadImage($_FILES['image']);

                if ($uploadResult['success']) {
                    $newImageFilename = $uploadResult['filename'];
                } else {
                    $errors[] = $uploadResult['message'];
                }
            }
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/edit/' . $id);
            return;
        }

        // Preparar datos para actualizar
        $updateData = [
            'title' => $title,
            'description' => $description,
            'category_id' => $categoryId
        ];

        // Si hay nueva imagen, agregarla a los datos
        if ($newImageFilename) {
            $updateData['image_path'] = $newImageFilename;
        }

        // Actualizar publicación
        $result = $this->postModel->update($id, $updateData);

        if ($result['success']) {
            // Si se actualizó la imagen, eliminar la anterior
            if ($newImageFilename && !empty($post['image_path'])) {
                deleteFile($post['image_path']);
            }

            $this->setFlash('success', 'Publicación actualizada exitosamente');
            $this->redirect('admin');
        } else {
            // Si falló, eliminar la nueva imagen
            if ($newImageFilename) {
                deleteFile($newImageFilename);
            }

            $this->setFlash('error', $result['message']);
            $this->redirect('admin/edit/' . $id);
        }
    }

    /**
     * Eliminar publicación
     */
    public function delete($id)
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin');
            return;
        }

        $result = $this->postModel->delete($id);

        if ($result['success']) {
            $this->setFlash('success', 'Publicación eliminada exitosamente');
        } else {
            $this->setFlash('error', $result['message']);
        }

        $this->redirect('admin');
    }
}
