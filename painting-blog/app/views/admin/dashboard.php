<div class="admin-page">
    <div class="container">
        <div class="admin-header">
            <div>
                <h1 class="admin-title">Panel de Administración</h1>
                <p class="admin-subtitle">Bienvenido, <?= escapeHtml($user['username']) ?></p>
            </div>
            <a href="<?= BASE_URL ?>/admin/create" class="btn btn-primary">
                + Nueva Publicación
            </a>
        </div>

        <div class="admin-stats">
            <div class="stat-card">
                <div class="stat-number"><?= $totalPosts ?></div>
                <div class="stat-label">Publicaciones</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($categories) ?></div>
                <div class="stat-label">Categorías</div>
            </div>
        </div>

        <div class="admin-content">
            <h2 class="section-title">Todas las Publicaciones</h2>

            <?php if (!empty($posts)): ?>
                <div class="posts-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td>
                                        <img src="<?= UPLOAD_URL ?>/<?= escapeHtml($post['image_path']) ?>"
                                            alt="<?= escapeHtml($post['title']) ?>" class="table-thumbnail">
                                    </td>
                                    <td>
                                        <strong><?= escapeHtml($post['title']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge"><?= escapeHtml($post['category_name']) ?></span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($post['created_at'])) ?></td>
                                    <td class="table-actions">
                                        <a href="<?= BASE_URL ?>/post/<?= $post['slug'] ?>" class="btn-icon" title="Ver"
                                            target="_blank">👁️</a>
                                        <a href="<?= BASE_URL ?>/admin/edit/<?= $post['id'] ?>" class="btn-icon"
                                            title="Editar">✏️</a>
                                        <form action="<?= BASE_URL ?>/admin/delete/<?= $post['id'] ?>" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('¿Estás seguro de eliminar esta publicación?');">
                                            <button type="submit" class="btn-icon" title="Eliminar">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p class="empty-message">No hay publicaciones todavía.</p>
                    <a href="<?= BASE_URL ?>/admin/create" class="btn btn-primary">Crear primera publicación</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>