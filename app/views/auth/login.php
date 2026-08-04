<?php require_once 'app/views/layouts/header.php'; ?>

<div class="login-wrapper">
    <div class="glass-card login-card">
        
        <div class="auth-header">
            <div class="logo-icon">📖</div>
            <h2>Bienvenido de nuevo</h2>
            <p class="subtitle">Dale una nueva historia a tus libros</p>
        </div>

        <?php if (isset($data['exito'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($data['exito']) ?></div>
        <?php endif; ?>

        <?php if (isset($data['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/login" method="POST">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="ejemplo@universidad.edu">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary" id="btn-iniciarSesion">
                Iniciar sesión ➔
            </button>

            <div class="divider">o</div>

            <div class="auth-footer">
                <p>¿No tienes una cuenta? <a href="<?= BASE_URL ?>/registro/index">Regístrate gratis</a></p>
            </div>
        </form>

    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>