<?php
/**
 * Modelo User - Gestión de usuarios
 * Implementa autenticación segura con password_hash()
 */
class User
{
    private $db;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;
    public $created_at;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Crear un nuevo usuario
     */
    public function create($username, $email, $password)
    {
        // Validar que el username no exista
        if ($this->findByUsername($username)) {
            return ['success' => false, 'message' => 'El nombre de usuario ya existe'];
        }

        // Validar que el email no exista
        if ($this->findByEmail($email)) {
            return ['success' => false, 'message' => 'El email ya está registrado'];
        }

        // Hash de la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO {$this->table} (username, email, password, created_at) 
                VALUES (:username, :email, :password, NOW())";

        $params = [
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword
        ];

        if ($this->db->execute($sql, $params)) {
            return ['success' => true, 'message' => 'Usuario creado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al crear el usuario'];
    }

    /**
     * Autenticar usuario
     */
    public function authenticate($username, $password)
    {
        $user = $this->findByUsername($username);

        if (!$user) {
            return ['success' => false, 'message' => 'Credenciales incorrectas'];
        }

        // Verificar contraseña
        if (password_verify($password, $user['password'])) {
            return [
                'success' => true,
                'user' => $user,
                'message' => 'Autenticación exitosa'
            ];
        }

        return ['success' => false, 'message' => 'Credenciales incorrectas'];
    }

    /**
     * Buscar usuario por ID
     */
    public function findById($id)
    {
        $sql = "SELECT id, username, email, created_at FROM {$this->table} WHERE id = :id";
        return $this->db->queryOne($sql, [':id' => $id]);
    }

    /**
     * Buscar usuario por username
     */
    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        return $this->db->queryOne($sql, [':username' => $username]);
    }

    /**
     * Buscar usuario por email
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->db->queryOne($sql, [':email' => $email]);
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "UPDATE {$this->table} SET password = :password WHERE id = :id";

        return $this->db->execute($sql, [
            ':password' => $hashedPassword,
            ':id' => $userId
        ]);
    }

    /**
     * Obtener todos los usuarios
     */
    public function findAll()
    {
        $sql = "SELECT id, username, email, created_at FROM {$this->table} ORDER BY created_at DESC";
        return $this->db->query($sql);
    }

    /**
     * Eliminar usuario
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->db->execute($sql, [':id' => $id]);
    }
}
