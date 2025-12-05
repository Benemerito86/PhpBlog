<?php
/**
 * Modelo Post - Gestión de publicaciones de pinturas
 * Implementa operaciones CRUD completas
 */
class Post
{
    private $db;
    private $table = 'posts';

    public $id;
    public $title;
    public $slug;
    public $description;
    public $image;
    public $category_id;
    public $user_id;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Crear una nueva publicación
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (title, slug, description, image, category_id, user_id, created_at, updated_at) 
                VALUES (:title, :slug, :description, :image, :category_id, :user_id, NOW(), NOW())";

        $params = [
            ':title' => $data['title'],
            ':slug' => $this->generateSlug($data['title']),
            ':description' => $data['description'],
            ':image' => $data['image'],
            ':category_id' => $data['category_id'],
            ':user_id' => $data['user_id']
        ];

        if ($this->db->execute($sql, $params)) {
            return [
                'success' => true,
                'id' => $this->db->lastInsertId(),
                'message' => 'Publicación creada exitosamente'
            ];
        }

        return ['success' => false, 'message' => 'Error al crear la publicación'];
    }

    /**
     * Obtener todas las publicaciones con información de categoría y usuario
     */
    public function findAll($limit = null, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, u.username 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.user_id = u.id
                ORDER BY p.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        return $this->db->query($sql);
    }

    /**
     * Buscar publicación por ID
     */
    public function findById($id)
    {
        $sql = "SELECT p.*, c.name as category_name, u.username 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.id = :id";

        return $this->db->queryOne($sql, [':id' => $id]);
    }

    /**
     * Buscar publicación por slug
     */
    public function findBySlug($slug)
    {
        $sql = "SELECT p.*, c.name as category_name, u.username 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.slug = :slug";

        return $this->db->queryOne($sql, [':slug' => $slug]);
    }

    /**
     * Obtener publicaciones por categoría
     */
    public function findByCategory($categoryId, $limit = null)
    {
        $sql = "SELECT p.*, c.name as category_name, u.username 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.category_id = :category_id
                ORDER BY p.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT :limit";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':category_id', (int) $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        return $this->db->query($sql, [':category_id' => $categoryId]);
    }

    /**
     * Actualizar publicación
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET title = :title, 
                    slug = :slug,
                    description = :description, 
                    category_id = :category_id,
                    updated_at = NOW()";

        $params = [
            ':title' => $data['title'],
            ':slug' => $this->generateSlug($data['title']),
            ':description' => $data['description'],
            ':category_id' => $data['category_id'],
            ':id' => $id
        ];

        // Si hay nueva imagen, actualizar también
        if (isset($data['image']) && !empty($data['image'])) {
            $sql .= ", image = :image";
            $params[':image'] = $data['image'];
        }

        $sql .= " WHERE id = :id";

        if ($this->db->execute($sql, $params)) {
            return ['success' => true, 'message' => 'Publicación actualizada exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar la publicación'];
    }

    /**
     * Eliminar publicación
     */
    public function delete($id)
    {
        // Obtener la imagen para eliminarla del servidor
        $post = $this->findById($id);

        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        if ($this->db->execute($sql, [':id' => $id])) {
            // Eliminar imagen del servidor si existe
            if ($post && !empty($post['image'])) {
                $imagePath = UPLOAD_PATH . '/' . $post['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            return ['success' => true, 'message' => 'Publicación eliminada exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al eliminar la publicación'];
    }

    /**
     * Generar slug a partir del título
     */
    private function generateSlug($title)
    {
        // Convertir a minúsculas
        $slug = strtolower($title);

        // Reemplazar caracteres especiales
        $slug = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $slug);

        // Reemplazar espacios y caracteres no alfanuméricos por guiones
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        // Eliminar guiones al inicio y final
        $slug = trim($slug, '-');

        // Verificar si el slug ya existe
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Verificar si un slug ya existe
     */
    private function slugExists($slug)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $result = $this->db->queryOne($sql, [':slug' => $slug]);
        return $result['count'] > 0;
    }

    /**
     * Contar total de publicaciones
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->queryOne($sql);
        return $result['total'];
    }

    /**
     * Obtener publicaciones recientes
     */
    public function getRecent($limit = 5)
    {
        return $this->findAll($limit);
    }
}
