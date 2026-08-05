<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="page-head">
    <div>
        <span class="eyebrow">CUENTA</span>
        <h1>Mi perfil</h1>
    </div>
</section>

<div class="card form-card">
    <?php if (!empty($data['flash'])): ?>
        <div class="alert <?= $data['flash']['type'] === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($data['flash']['message']) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/perfil/actualizar">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($data['csrf']) ?>">

        <div class="form-grid">
            <label>
                Nombre
                <input name="nombre" value="<?= htmlspecialchars($data['usuario']['nombre']) ?>" required>
            </label>
            <label>
                Apellidos
                <input
                    name="apellidos"
                    value="<?= htmlspecialchars($data['usuario']['apellidos']) ?>"
                    required
                >
            </label>
        </div>

        <label>
            Correo
            <input value="<?= htmlspecialchars($data['usuario']['correo']) ?>" disabled>
        </label>

        <div class="form-grid">
            <label>
                Institución
                <select name="institucion_id" required>
                    <?php foreach ($data['instituciones'] as $institucion): ?>
                        <option
                            value="<?= $institucion['id'] ?>"
                            <?= $institucion['id'] == $data['usuario']['institucion_id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($institucion['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                Carrera
                <select name="carrera_id" required>
                    <?php foreach ($data['carreras'] as $carrera): ?>
                        <option
                            value="<?= $carrera['id'] ?>"
                            <?= $carrera['id'] == $data['usuario']['carrera_id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($carrera['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <button class="btn primary">Guardar cambios</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
