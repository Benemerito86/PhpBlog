<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Galería de Pinturas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="auth-page">
        <div class="container">
            <div class="auth-card">
                <h1 class="auth-title">🎨 Galería de Pinturas</h1>
                <p class="auth-subtitle">Inicia sesión para publicar y ver pinturas</p>

                <?php if (isset($data['error'])): ?>
                    <div class="alert alert-error">
                        <?= escapeHtml($data['error']) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/login" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" id="username" name="username" class="form-input" required autofocus
                            placeholder="admin o usuario">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-input" required
                            placeholder="••••••••">
                        <small class="form-help">
                            Demo: admin/admin123 o usuario/user123
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Iniciar Sesión
                    </button>
                </form>

                <div class="auth-footer">
                    <p>¿No tienes cuenta? <a href="<?= BASE_URL ?>/register" class="auth-link">Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>