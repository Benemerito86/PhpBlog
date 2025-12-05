<div class="admin-page">
    <div class="container">
        <div class="admin-header">
            <h1 class="admin-title">Nueva Publicación</h1>
            <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">← Volver</a>
        </div>

        <div class="form-container">
            <form action="<?= BASE_URL ?>/admin/create" method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label for="title" class="form-label">Título *</label>
                    <input type="text" id="title" name="title" class="form-input" required maxlength="200" autofocus>
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label">Categoría *</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>">
                                <?= escapeHtml($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Descripción *</label>
                    <textarea id="description" name="description" class="form-textarea" rows="6" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Imagen *</label>
                    <input type="file" id="image" name="image" class="form-file"
                        accept="image/jpeg,image/png,image/gif,image/webp" required onchange="previewImage(event)">
                    <small class="form-help">Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 5MB</small>
                </div>

                <div id="imagePreview" class="image-preview" style="display: none;">
                    <img id="preview" src="" alt="Vista previa">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Publicar</button>
                    <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
</script>