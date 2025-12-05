# 🎨 Blog de Pinturas - Galería de Arte

Blog profesional para publicar y gestionar pinturas, desarrollado con **PHP puro** aplicando conceptos avanzados de programación.

## 🚀 Características

- ✅ **Arquitectura MVC** - Separación clara de responsabilidades
- ✅ **Enrutamiento Amigable** - URLs limpias como `/post/mi-pintura`
- ✅ **POO Completa** - Clases bien estructuradas (Database, User, Post, Category)
- ✅ **PDO con Sentencias Preparadas** - Protección contra inyección SQL
- ✅ **Autenticación Segura** - Contraseñas hasheadas con `password_hash()`
- ✅ **Gestión de Sesiones** - Sistema de login/logout robusto
- ✅ **Subida de Archivos** - Validación completa de imágenes
- ✅ **Sistema de Plantillas** - Separación de lógica y presentación
- ✅ **Validación y Saneamiento** - Protección XSS y validación de datos
- ✅ **Diseño Moderno** - Interfaz premium con dark mode y animaciones

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior / MariaDB
- Apache con mod_rewrite habilitado
- Extensión PDO de PHP
- Extensión GD de PHP (para procesamiento de imágenes)

## 🛠️ Instalación

### 1. Clonar o descargar el proyecto

```bash
cd /var/www/html  # o tu directorio de proyectos
```

### 2. Configurar la base de datos

```bash
# Acceder a MySQL
mysql -u root -p

# Ejecutar el esquema
mysql -u root -p < database/schema.sql
```

O importar manualmente desde phpMyAdmin.

### 3. Configurar la aplicación

Editar `app/config/config.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'painting_blog');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');

define('BASE_URL', 'http://localhost/painting-blog/public');
```

### 4. Configurar permisos

```bash
# Dar permisos de escritura al directorio de uploads
chmod 755 public/uploads
```

### 5. Configurar Apache (si es necesario)

Asegúrate de que `mod_rewrite` esté habilitado:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## 🎯 Uso

### Acceso Público

Visita `http://localhost/painting-blog/public` para ver la galería pública.

### Panel de Administración

1. **Registrarse**: Ve a `/register` para crear una cuenta
2. **Iniciar sesión**: Ve a `/login` con tus credenciales
3. **Administrar**: Accede a `/admin` para gestionar publicaciones

**Usuario de prueba incluido:**
- Usuario: `admin`
- Contraseña: `admin123`

### Crear Publicaciones

1. Inicia sesión
2. Ve al panel de administración
3. Click en "Nueva Publicación"
4. Completa el formulario:
   - Título de la pintura
   - Categoría
   - Descripción
   - Imagen (JPG, PNG, GIF, WEBP - máx 5MB)
5. Click en "Publicar"

## 📁 Estructura del Proyecto

```
painting-blog/
├── app/
│   ├── config/           # Configuración
│   │   └── config.php
│   ├── core/             # Núcleo del framework
│   │   ├── Router.php    # Sistema de enrutamiento
│   │   ├── Controller.php # Controlador base
│   │   └── View.php      # Motor de plantillas
│   ├── models/           # Modelos (POO)
│   │   ├── Database.php  # Conexión PDO
│   │   ├── User.php      # Modelo de usuarios
│   │   ├── Post.php      # Modelo de publicaciones
│   │   └── Category.php  # Modelo de categorías
│   ├── controllers/      # Controladores
│   │   ├── HomeController.php
│   │   ├── PostController.php
│   │   ├── AuthController.php
│   │   └── AdminController.php
│   ├── views/            # Vistas
│   │   ├── layouts/      # Plantillas base
│   │   ├── home/         # Vistas públicas
│   │   ├── posts/        # Detalle de pinturas
│   │   ├── auth/         # Login/Registro
│   │   └── admin/        # Panel admin
│   └── helpers/          # Funciones auxiliares
│       ├── validation.php
│       └── upload.php
├── public/               # Directorio público
│   ├── index.php        # Front controller
│   ├── .htaccess        # Reescritura URLs
│   ├── css/
│   ├── js/
│   └── uploads/         # Imágenes subidas
└── database/
    └── schema.sql       # Esquema de BD
```

## 🔒 Seguridad

- **Contraseñas**: Hasheadas con `password_hash()` (bcrypt)
- **SQL Injection**: Protección con sentencias preparadas PDO
- **XSS**: Escapado de HTML con `htmlspecialchars()`
- **Validación**: Validación en servidor de todos los inputs
- **Archivos**: Validación de tipo MIME y extensión
- **Sesiones**: Gestión segura de sesiones PHP

## 🎨 Conceptos Clave Implementados

### 1. Enrutamiento
Sistema personalizado que convierte URLs amigables en llamadas a controladores:
- `/` → HomeController::index()
- `/post/mi-pintura` → PostController::show('mi-pintura')
- `/admin` → AdminController::dashboard()

### 2. POO (Programación Orientada a Objetos)
Clases bien estructuradas con responsabilidades claras:
- **Database**: Singleton para conexión PDO
- **User**: Autenticación y gestión de usuarios
- **Post**: CRUD completo de pinturas
- **Category**: Gestión de categorías

### 3. PDO con Sentencias Preparadas
```php
$sql = "SELECT * FROM posts WHERE id = :id";
$this->db->queryOne($sql, [':id' => $id]);
```

### 4. Autenticación Segura
```php
// Registro
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Login
password_verify($password, $user['password']);
```

### 5. Gestión de Archivos
Validación completa de imágenes subidas:
- Tipo MIME
- Extensión
- Tamaño
- Nombres únicos

## 🌐 Rutas Disponibles

### Públicas
- `GET /` - Galería principal
- `GET /post/{slug}` - Detalle de pintura
- `GET /category/{id}` - Filtrar por categoría

### Autenticación
- `GET /login` - Formulario de login
- `POST /login` - Procesar login
- `GET /register` - Formulario de registro
- `POST /register` - Procesar registro
- `GET /logout` - Cerrar sesión

### Administración (requiere login)
- `GET /admin` - Panel de administración
- `GET /admin/create` - Formulario nueva publicación
- `POST /admin/create` - Guardar publicación
- `GET /admin/edit/{id}` - Formulario editar
- `POST /admin/update/{id}` - Actualizar publicación
- `POST /admin/delete/{id}` - Eliminar publicación

## 🎓 Aprendizaje

Este proyecto te enseña:

1. **Arquitectura MVC** en PHP puro
2. **Enrutamiento personalizado** sin frameworks
3. **POO aplicada** a casos reales
4. **Seguridad web** (SQL injection, XSS, autenticación)
5. **Gestión de archivos** y validación
6. **Separación de responsabilidades**
7. **Buenas prácticas** de desarrollo

## 📝 Licencia

Este proyecto es de código abierto y está disponible para fines educativos.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:
1. Fork el proyecto
2. Crea una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abre un Pull Request

## 📧 Contacto

Para preguntas o sugerencias, abre un issue en el repositorio.

---

**Desarrollado con ❤️ usando PHP, POO y PDO**
