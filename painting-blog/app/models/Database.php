<?php
/**
 * Clase Database - Almacenamiento JSON con interfaz PDO
 */
class Database
{
    private static $instance = null;
    private $dataPath;
    private $connection; // Para compatibilidad

    private function __construct()
    {
        $this->dataPath = ROOT_PATH . '/database';
        $this->connection = $this; // Auto-referencia para getConnection()

        if (!file_exists($this->dataPath)) {
            mkdir($this->dataPath, 0755, true);
        }

        $this->initializeData();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this;
    }

    private function initializeData()
    {
        // Users
        if (!file_exists($this->dataPath . '/users.json')) {
            $users = [
                ['id' => 1, 'username' => 'admin', 'email' => 'admin@blog.com', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'role' => 'admin', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 2, 'username' => 'usuario', 'email' => 'user@blog.com', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'role' => 'user', 'created_at' => date('Y-m-d H:i:s')]
            ];
            file_put_contents($this->dataPath . '/users.json', json_encode($users, JSON_PRETTY_PRINT));
        }

        // Categories
        if (!file_exists($this->dataPath . '/categories.json')) {
            $categories = [
                ['id' => 1, 'name' => 'Acrílica', 'slug' => 'acrilica', 'description' => 'Pinturas acrílicas', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 2, 'name' => 'Óleo', 'slug' => 'oleo', 'description' => 'Pinturas al óleo', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 3, 'name' => 'Acuarela', 'slug' => 'acuarela', 'description' => 'Acuarelas', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 4, 'name' => 'Digital', 'slug' => 'digital', 'description' => 'Arte digital', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 5, 'name' => 'Pastel', 'slug' => 'pastel', 'description' => 'Pasteles', 'created_at' => date('Y-m-d H:i:s')]
            ];
            file_put_contents($this->dataPath . '/categories.json', json_encode($categories, JSON_PRETTY_PRINT));
        }

        // Posts
        if (!file_exists($this->dataPath . '/posts.json')) {
            $posts = [
                ['id' => 1, 'title' => 'Atardecer en la Montaña', 'slug' => 'atardecer-montana', 'content' => 'Hermoso atardecer.', 'excerpt' => 'Atardecer vibrante', 'image' => 'atardecer-montana.jpg', 'category_id' => 1, 'user_id' => 1, 'status' => 'published', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 2, 'title' => 'Retrato Abstracto', 'slug' => 'retrato-abstracto', 'content' => 'Exploración abstracta.', 'excerpt' => 'Retrato geométrico', 'image' => 'retrato-abstracto.jpg', 'category_id' => 2, 'user_id' => 1, 'status' => 'published', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 3, 'title' => 'Jardín de Primavera', 'slug' => 'jardin-primavera', 'content' => 'Acuarela delicada.', 'excerpt' => 'Jardín en acuarela', 'image' => 'jardin-primavera.jpg', 'category_id' => 3, 'user_id' => 1, 'status' => 'published', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 4, 'title' => 'Paisaje Urbano', 'slug' => 'paisaje-urbano', 'content' => 'Arte digital.', 'excerpt' => 'Ciudad de noche', 'image' => 'urbano-nocturno.jpg', 'category_id' => 4, 'user_id' => 1, 'status' => 'published', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 5, 'title' => 'Naturaleza Muerta', 'slug' => 'naturaleza-muerta', 'content' => 'Composición clásica.', 'excerpt' => 'Frutas y flores', 'image' => 'naturaleza-muerta.jpg', 'category_id' => 5, 'user_id' => 1, 'status' => 'published', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 6, 'title' => 'Mar en Calma', 'slug' => 'mar-calma', 'content' => 'Serenidad del mar.', 'excerpt' => 'Mar sereno', 'image' => 'mar-calma.jpg', 'category_id' => 1, 'user_id' => 2, 'status' => 'published', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 7, 'title' => 'Bosque Otoñal', 'slug' => 'bosque-otonal', 'content' => 'Colores cálidos.', 'excerpt' => 'Bosque en otoño', 'image' => 'bosque-otonal.jpg', 'category_id' => 2, 'user_id' => 2, 'status' => 'published', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 8, 'title' => 'Flores Silvestres', 'slug' => 'flores-silvestres', 'content' => 'Flores del campo.', 'excerpt' => 'Flores delicadas', 'image' => 'flores-silvestres.jpg', 'category_id' => 3, 'user_id' => 2, 'status' => 'published', 'created_at' => date('Y-m-d H:i:s')]
            ];
            file_put_contents($this->dataPath . '/posts.json', json_encode($posts, JSON_PRETTY_PRINT));
        }
    }

    private function readJson($table)
    {
        $file = $this->dataPath . '/' . $table . '.json';
        if (!file_exists($file))
            return [];
        return json_decode(file_get_contents($file), true) ?: [];
    }

    private function writeJson($table, $data)
    {
        file_put_contents($this->dataPath . '/' . $table . '.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public function query($sql, $params = [])
    {
        // Parse simple SQL
        if (preg_match('/FROM\s+(\w+)/i', $sql, $matches)) {
            $table = $matches[1];
            $data = $this->readJson($table);

            // Handle JOINs - specifically for posts + categories
            if (preg_match('/LEFT JOIN\s+categories\s+c\s+ON/i', $sql)) {
                $categories = $this->readJson('categories');

                foreach ($data as &$row) {
                    foreach ($categories as $cat) {
                        if ($row['category_id'] == $cat['id']) {
                            $row['category_name'] = $cat['name'];
                            break;
                        }
                    }
                }
            }

            // Handle WHERE
            if (preg_match('/WHERE\s+(.+?)(?:ORDER|LIMIT|$)/i', $sql, $whereMatch)) {
                $data = $this->applyWhere($data, $whereMatch[1], $params);
            }

            return array_values($data);
        }
        return [];
    }

    public function queryOne($sql, $params = [])
    {
        $results = $this->query($sql, $params);
        return !empty($results) ? $results[0] : false;
    }

    public function execute($sql, $params = [])
    {
        if (preg_match('/INSERT\s+INTO\s+(\w+)/i', $sql, $matches)) {
            $table = $matches[1];
            $data = $this->readJson($table);
            $newId = empty($data) ? 1 : max(array_column($data, 'id')) + 1;

            $newRecord = ['id' => $newId];
            foreach ($params as $key => $value) {
                $newRecord[ltrim($key, ':')] = $value;
            }

            $data[] = $newRecord;
            $this->writeJson($table, $data);
            return true;
        }

        if (preg_match('/DELETE\s+FROM\s+(\w+)\s+WHERE\s+id\s*=\s*:id/i', $sql)) {
            $table = $matches[1];
            $data = $this->readJson($table);
            $data = array_filter($data, fn($row) => $row['id'] != $params[':id']);
            $this->writeJson($table, array_values($data));
            return true;
        }

        return false;
    }

    private function applyWhere($data, $where, $params)
    {
        if (preg_match('/(\w+)\.?(\w+)?\s*=\s*:(\w+)/', $where, $matches)) {
            $field = $matches[2] ?: $matches[1];
            $paramKey = ':' . $matches[3];
            $value = $params[$paramKey] ?? null;

            return array_filter($data, fn($row) => isset($row[$field]) && $row[$field] == $value);
        }
        return $data;
    }

    public function lastInsertId()
    {
        return 1;
    }

    public function prepare($sql)
    {
        return new class ($this, $sql) {
            private $db;
            private $sql;
            private $params = [];

            public function __construct($db, $sql)
            {
                $this->db = $db;
                $this->sql = $sql;
            }

            public function bindValue($param, $value, $type = null)
            {
                $this->params[$param] = $value;
            }

            public function execute($params = null)
            {
                if ($params)
                    $this->params = array_merge($this->params, $params);
                return true;
            }

            public function fetchAll()
            {
                return $this->db->query($this->sql, $this->params);
            }

            public function fetch()
            {
                return $this->db->queryOne($this->sql, $this->params);
            }
        };
    }

    private function __clone()
    {
    }
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize");
    }
}
