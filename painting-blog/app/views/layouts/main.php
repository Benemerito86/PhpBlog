<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? escapeHtml($title) . ' - ' : '' ?><?= APP_NAME ?></title>
    <meta name="description" content="<?= APP_DESCRIPTION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
</head>

<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="<?= BASE_URL ?>" class="logo">
                    <span class="logo-icon">🎨</span>
                    <?= APP_NAME ?>
                </a>

                <ul class="nav-menu">
                    <li><a href="<?= BASE_URL ?>">Galería</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?= BASE_URL ?>/admin">Panel Admin</a></li>
                        <li><a href="<?= BASE_URL ?>/logout" class="btn-logout">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="<?= BASE_URL ?>/login" class="btn-login">Iniciar Sesión</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <?php if (isset($_SESSION['flash'])): ?>
            <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                <div class="alert alert-<?= $type ?>">
                    <div class="container">
                        <?= $message ?>
                    </div>
                </div>
                <?php unset($_SESSION['flash'][$type]); ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.</p>
            <p class="footer-subtitle">Desarrollado con PHP, POO y PDO</p>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>/js/main.js"></script>
</body>

</html>