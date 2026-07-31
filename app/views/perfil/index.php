<?php require_once 'app/views/layouts/header.php'; ?>

<div class="login-wrapper">
    <div class="glass-card profile-card">
        <div class="auth-header">
            <div class="logo-icon">👤</div>
            <h2>Mi Perfil</h2>
            <p class="subtitle">Gestiona tu información personal</p>
        </div>

        <?php if (!empty($data['mensaje'])): ?>
            <div class="alert alert-<?= $data['mensaje']['tipo'] === 'exito' ? 'success' : 'danger' ?>">
                <?= $data['mensaje']['texto'] ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/perfil/actualizar" method="POST" class="auth-form">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($data['usuario']['nombre'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" class="form-control" value="<?= htmlspecialchars($data['usuario']['apellidos'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico (No editable)</label>
                <input type="email" id="correo" class="form-control input-disabled" value="<?= htmlspecialchars($data['usuario']['correo'] ?? '') ?>" disabled readonly>
            </div>

            <div class="form-group">
                <label for="institucion">Universidad / Institución</label>
                <input type="text" id="institucion" class="form-control input-disabled" value="<?= htmlspecialchars($data['usuario']['institucion'] ?? 'No especificada') ?>" disabled readonly>
            </div>

            <div class="form-group">
                <label for="carrera">Carrera</label>
                <input type="text" id="carrera" class="form-control input-disabled" value="<?= htmlspecialchars($data['usuario']['carrera'] ?? 'No especificada') ?>" disabled readonly>
            </div>

            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </form>

        <div class="auth-footer" style="margin-top: 20px;">
            <a href="<?= BASE_URL ?>/auth/logout" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>