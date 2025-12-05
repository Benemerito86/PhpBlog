<?php
require_once '../app/core/Controller.php';

/**
 * HomeController - Controlador de la página principal
 */
class HomeController extends Controller
{
    private $postModel;
    private $categoryModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
        $this->categoryModel = $this->model('Category');
    }

    /**
     * Página principal - Galería de pinturas
     */
    public function index()
    {
        $posts = $this->postModel->findAll();
        $categories = $this->categoryModel->findAllWithCount();

        $data = [
            'title' => 'Galería de Pinturas',
            'posts' => $posts,
            'categories' => $categories
        ];

        $this->view('home/index', $data);
    }

    /**
     * Filtrar por categoría
     */
    public function category($categoryId)
    {
        $category = $this->categoryModel->findById($categoryId);

        if (!$category) {
            $this->redirect('');
            return;
        }

        $posts = $this->postModel->findByCategory($categoryId);
        $categories = $this->categoryModel->findAllWithCount();

        $data = [
            'title' => 'Categoría: ' . $category['name'],
            'posts' => $posts,
            'categories' => $categories,
            'currentCategory' => $category
        ];

        $this->view('home/index', $data);
    }
}
