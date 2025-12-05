<div class="hero">
    <div class="container">
        <h1 class="hero-title">Galería de Pinturas</h1>
        <p class="hero-subtitle">Descubre obras de arte únicas</p>
    </div>
</div>

<?php if (!empty($categories)): ?>
    <div class="categories-section">
        <div class="container">
            <div class="categories-filter">
                <a href="<?= BASE_URL ?>" class="category-tag <?= !isset($currentCategory) ? 'active' : '' ?>">
                    Todas
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="<?= BASE_URL ?>/category/<?= $category['id'] ?>"
                        class="category-tag <?= isset($currentCategory) && $currentCategory['id'] == $category['id'] ? 'active' : '' ?>">
                        <?= escapeHtml($category['name']) ?>
                        <span class="category-count"><?= $category['post_count'] ?? 0 ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="gallery-section">
    <div class="container">
        <?php if (isset($currentCategory)): ?>
            <h2 class="section-title">Categoría: <?= escapeHtml($currentCategory['name']) ?></h2>
        <?php endif; ?>

        <?php if (!empty($posts)): ?>
            <div class="gallery-grid">
                <?php foreach ($posts as $post): ?>
                    <article class="painting-card">
                        <a href="<?= BASE_URL ?>/post/<?= $post['slug'] ?>" class="painting-link">
                            <div class="painting-image-wrapper">
                                <img src="<?= UPLOAD_URL ?>/<?= escapeHtml($post['image']) ?>"
                                    alt="<?= escapeHtml($post['title']) ?>" class="painting-image" loading="lazy">
                                <div class="painting-overlay">
                                    <span class="view-details">Ver detalles</span>
                                </div>
                            </div>
                            <div class="painting-info">
                                <h3 class="painting-title"><?= escapeHtml($post['title']) ?></h3>
                                <p class="painting-meta">
                                    <span class="painting-category"><?= escapeHtml($post['category_name']) ?></span>
                                    <span class="painting-date"><?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                                </p>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p class="empty-message">No hay pinturas disponibles en esta categoría.</p>
                <a href="<?= BASE_URL ?>" class="btn btn-primary">Ver todas las pinturas</a>
            </div>
        <?php endif; ?>
    </div>
</div>