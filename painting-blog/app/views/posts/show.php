<div class="post-detail">
    <div class="container">
        <div class="post-header">
            <a href="<?= BASE_URL ?>" class="back-link">← Volver a la galería</a>

            <div class="post-meta-header">
                <span class="post-category"><?= escapeHtml($post['category_name']) ?></span>
                <span class="post-date"><?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
            </div>

            <h1 class="post-title"><?= escapeHtml($post['title']) ?></h1>
        </div>

        <div class="post-image-container">
            <img src="<?= UPLOAD_URL ?>/<?= escapeHtml($post['image_path']) ?>" alt="<?= escapeHtml($post['title']) ?>"
                class="post-image">
        </div>

        <div class="post-content">
            <div class="post-description">
                <?= nl2br(escapeHtml($post['description'])) ?>
            </div>

            <div class="post-author">
                <p>Publicado por <strong><?= escapeHtml($post['username']) ?></strong></p>
            </div>
        </div>

        <?php if (!empty($relatedPosts)): ?>
            <div class="related-posts">
                <h2 class="section-title">Pinturas Relacionadas</h2>
                <div class="related-grid">
                    <?php foreach (array_slice($relatedPosts, 0, 3) as $related): ?>
                        <article class="painting-card">
                            <a href="<?= BASE_URL ?>/post/<?= $related['slug'] ?>" class="painting-link">
                                <div class="painting-image-wrapper">
                                    <img src="<?= UPLOAD_URL ?>/<?= escapeHtml($related['image_path']) ?>"
                                        alt="<?= escapeHtml($related['title']) ?>" class="painting-image" loading="lazy">
                                    <div class="painting-overlay">
                                        <span class="view-details">Ver detalles</span>
                                    </div>
                                </div>
                                <div class="painting-info">
                                    <h3 class="painting-title"><?= escapeHtml($related['title']) ?></h3>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>