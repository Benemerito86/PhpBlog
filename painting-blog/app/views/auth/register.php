<div class="auth-page">
    <div class="container">
        <div class="auth-card">
            <h1 class="auth-title">Crear Cuenta</h1>
            <p class="auth-subtitle">Regístrate para administrar el blog</p>

            <form action="<?= BASE_URL ?>/register" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" id="username" name="username" class="form-input" required
                        pattern="[a-zA-Z0-9_]{3,20}" title="Solo letras, números y guiones bajos (3-20 caracteres)"
                        autofocus>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-input" required minlength="6">
                    <small class="form-help">Mínimo 6 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" required
                        minlength="6">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Crear Cuenta
                </button>
            </form>

            <p class="auth-footer">
                ¿Ya tienes cuenta?
                <a href="<?= BASE_URL ?>/login" class="auth-link">Inicia sesión aquí</a>
            </p>
        </div>
    </div>
</div>