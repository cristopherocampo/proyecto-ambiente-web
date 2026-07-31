<?php require_once 'app/views/layouts/header.php'; ?>

<div class="login-wrapper">
    <div class="glass-card profile-card">
        <div class="auth-header">
            <div class="logo-icon">👤</div>
            <h2>Mi Perfil</h2>
            <p class="subtitle">Gestiona tu información personal y académica</p>
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
                <label for="institucion_id">Universidad / Institución</label>
                <select name="institucion_id" id="institucion_id" class="form-control" required>
                    <option value="">-- Selecciona una institución --</option>
                    <?php foreach ($data['instituciones'] as $inst): ?>
                        <option value="<?= $inst['id'] ?>" <?= ($data['usuario']['institucion_id'] == $inst['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($inst['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            
            <div class="form-group">
                <label for="carrera_id">Carrera</label>
                <select name="carrera_id" id="carrera_id" class="form-control" required>
                    <option value="">-- Selecciona una carrera --</option>
                    <?php foreach ($data['carreras'] as $carrera): ?>
                        <option value="<?= $carrera['id'] ?>" <?= ($data['usuario']['carrera_id'] == $carrera['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($carrera['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </form>

        <div class="auth-footer" style="margin-top: 20px;">
            <a href="<?= BASE_URL ?>/auth/logout" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>