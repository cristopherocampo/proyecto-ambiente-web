<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="auth-shell">
    <div class="card auth-card wide">
        <h1>Crear cuenta</h1>

        <?php if (!empty($data['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($data['error']) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/registro/registrar">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($data['csrf']) ?>">

            <div class="form-grid">
                <label>Nombre <input name="nombre" required></label>
                <label>Apellidos <input name="apellidos" required></label>
            </div>

            <label>
                Correo electrónico
                <input type="email" name="email" required>
            </label>

            <div class="form-grid">
                <label>
                    Institución
                    <select name="institucion_id" required>
                        <option value="">Selecciona</option>
                        <?php foreach ($data['instituciones'] as $institucion): ?>
                            <option value="<?= $institucion['id'] ?>">
                                <?= htmlspecialchars($institucion['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>
                    Carrera
                    <select name="carrera_id" required>
                        <option value="">Selecciona</option>
                        <?php foreach ($data['carreras'] as $carrera): ?>
                            <option value="<?= $carrera['id'] ?>">
                                <?= htmlspecialchars($carrera['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>

            <label>
                Contraseña (mínimo 8 caracteres)
                <input type="password" name="password" minlength="8" required>
            </label>

            <button class="btn primary">Crear cuenta</button>
        </form>

        <p class="center">
            <a href="<?= BASE_URL ?>/auth/index">Volver al inicio de sesión</a>
        </p>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
