<?php
$intercambio = $data['intercambio'];
$puntuacion = $data['puntuacion'] ?? '';
$comentario = $data['comentario'] ?? '';

require __DIR__ . '/../layouts/header.php';
?>

<section class="page-head">
    <div>
        <span class="eyebrow">VALORACIONES</span>
        <h1>Valorar intercambio</h1>
        <p>
            Califica tu experiencia con la otra persona después de completar el intercambio.
        </p>
    </div>
</section>

<?php if (!empty($data['errors'])): ?>
    <div class="alert error">
        <ul>
            <?php foreach ($data['errors'] as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form
    class="card form-card"
    method="post"
    action="<?= BASE_URL ?>/valoracion/guardar/<?= (int) $intercambio['id'] ?>"
>
    <input
        type="hidden"
        name="csrf"
        value="<?= htmlspecialchars($data['csrf']) ?>"
    >

    <div class="form-grid">
        <label>
            Material intercambiado
            <input
                type="text"
                value="<?= htmlspecialchars($intercambio['titulo']) ?>"
                disabled
            >
        </label>

        <label>
            Persona que vas a valorar
            <input
                type="text"
                value="<?= htmlspecialchars($intercambio['evaluado']) ?>"
                disabled
            >
        </label>
    </div>

    <label>
        Puntuación
        <select name="puntuacion" required>
            <option value="">Selecciona una puntuación</option>

            <option
                value="1"
                <?= (string) $puntuacion === '1' ? 'selected' : '' ?>
            >
                ★ 1 estrella
            </option>

            <option
                value="2"
                <?= (string) $puntuacion === '2' ? 'selected' : '' ?>
            >
                ★★ 2 estrellas
            </option>

            <option
                value="3"
                <?= (string) $puntuacion === '3' ? 'selected' : '' ?>
            >
                ★★★ 3 estrellas
            </option>

            <option
                value="4"
                <?= (string) $puntuacion === '4' ? 'selected' : '' ?>
            >
                ★★★★ 4 estrellas
            </option>

            <option
                value="5"
                <?= (string) $puntuacion === '5' ? 'selected' : '' ?>
            >
                ★★★★★ 5 estrellas
            </option>
        </select>
    </label>

    <label>
        Comentario
        <textarea
            name="comentario"
            rows="5"
            maxlength="600"
            placeholder="Contá brevemente cómo fue tu experiencia durante el intercambio."
        ><?= htmlspecialchars($comentario) ?></textarea>

        <small>
            El comentario es opcional y puede contener hasta 600 caracteres.
        </small>
    </label>

    <div class="form-actions">
        <a
            class="btn ghost"
            href="<?= BASE_URL ?>/intercambio/index"
        >
            Cancelar
        </a>

        <button class="btn primary">
            Guardar valoración
        </button>
    </div>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>