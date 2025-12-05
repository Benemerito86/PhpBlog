<?php
/**
 * Modelo Category - Gestión de categorías de pinturas
 */
class Category
{
    private $db;
    private $table = 'categories';

    public $id;
    public $name;
    public $slug;
    public $description;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Crear una nueva categoría
     */
    public function create($name, $description = '')
    {
        $sql = "INSERT INTO {$this->table} (name, slug, description) 
                VALUES (:name, :slug, :description)";

        $params = [
            ':name' => $name,
            ':slug' => $this->generateSlug($name),
            ':description' => $description
        ];

        if ($this->db->execute($sql, $params)) {
            return ['success' => true, 'message' => 'Categoría creada exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al crear la categoría'];
    }

    /**
     * Obtener todas las categorías
     */
    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
        return $this->db->query($sql);
    }

    /**
     * Buscar categoría por ID
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->queryOne($sql, [':id' => $id]);
    }

    /**
     * Buscar categoría por slug
     */
    public function findBySlug($slug)
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug";
        return $this->db->queryOne($sql, [':slug' => $slug]);
    }

    /**
     * Obtener categorías con conteo de publicaciones
     */
    public function findAllWithCount()
    {
        $sql = "SELECT c.*, COUNT(p.id) as post_count 
                FROM {$this->table} c
                LEFT JOIN posts p ON c.id = p.category_id
                GROUP BY c.id
                ORDER BY c.name ASC";

        return $this->db->query($sql);
    }

    /**
     * Actualizar categoría
     */
    public function update($id, $name, $description = '')
    {
        $sql = "UPDATE {$this->table} 
                SET name = :name, 
                    slug = :slug,
                    description = :description 
                WHERE id = :id";

        $params = [
            ':name' => $name,
            ':slug' => $this->generateSlug($name),
            ':description' => $description,
            ':id' => $id
        ];

        if ($this->db->execute($sql, $params)) {
            return ['success' => true, 'message' => 'Categoría actualizada exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar la categoría'];
    }

    /**
     * Eliminar categoría
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        if ($this->db->execute($sql, [':id' => $id])) {
            return ['success' => true, 'message' => 'Categoría eliminada exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al eliminar la categoría'];
    }

    /**
     * Generar slug a partir del nombre
     */
    private function generateSlug($name)
    {
        $slug = strtolower($name);
        $slug = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }
}
