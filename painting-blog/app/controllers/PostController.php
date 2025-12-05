<?php
require_once '../app/core/Controller.php';

/**
 * PostController - Controlador de publicaciones individuales
 */
class PostController extends Controller
{
    private $postModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
    }

    /**
     * Mostrar una publicación individual
     */
    public function show($slug)
    {
        $post = $this->postModel->findBySlug($slug);

        if (!$post) {
            $this->redirect('');
            return;
        }

        // Obtener publicaciones relacionadas de la misma categoría
        $relatedPosts = $this->postModel->findByCategory($post['category_id'], 4);

        // Filtrar la publicación actual de las relacionadas
        $relatedPosts = array_filter($relatedPosts, function ($p) use ($post) {
            return $p['id'] !== $post['id'];
        });

        $data = [
            'title' => $post['title'],
            'post' => $post,
            'relatedPosts' => $relatedPosts
        ];

        $this->view('posts/show', $data);
    }
}
