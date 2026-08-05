<?php
$publicacion = $data['publicacion'] ?? [];
$editing = !empty($publicacion['id']);
$selected = fn($key, $id) => (string) ($publicacion[$key] ?? '') === (string) $id
    ? 'selected'
    : '';
require __DIR__ . '/../layouts/header.php';
?>

<section class="page-head">
    <div>
        <span class="eyebrow">PUBLICACIONES</span>
        <h1><?= $editing ? 'Editar publicación' : 'Publicar material' ?></h1>
        <p>Completa la información para que otros estudiantes lo encuentren.</p>
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
    enctype="multipart/form-data"
    action="<?= BASE_URL ?>/publicaciones/<?= $editing
        ? 'actualizar/' . $publicacion['id']
        : 'guardar' ?>"
>
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($data['csrf']) ?>">

    <div class="form-grid">
        <label>
            Título
            <input
                name="titulo"
                maxlength="220"
                value="<?= htmlspecialchars($publicacion['titulo'] ?? '') ?>"
                required
            >
        </label>
        <label>
            Autor
            <input
                name="autor"
                maxlength="180"
                value="<?= htmlspecialchars($publicacion['autor'] ?? '') ?>"
                required
            >
        </label>
    </div>

    <label>
        Descripción
        <textarea name="descripcion" rows="4" required><?=
            htmlspecialchars($publicacion['descripcion'] ?? '')
        ?></textarea>
    </label>

    <div class="form-grid thirds">
        <label>
            Tipo
            <select name="tipo_material_id" required>
                <option value="">Selecciona</option>
                <?php foreach ($data['catalogos']['tipos'] as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>" <?= $selected('tipo_material_id', $tipo['id']) ?>>
                        <?= htmlspecialchars($tipo['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Categoría
            <select name="categoria_id" required>
                <option value="">Selecciona</option>
                <?php foreach ($data['catalogos']['categorias'] as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>" <?= $selected('categoria_id', $categoria['id']) ?>>
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Curso / carrera
            <select name="curso_id" required>
                <option value="">Selecciona</option>
                <?php foreach ($data['catalogos']['cursos'] as $curso): ?>
                    <?php $cursoNombre = ($curso['codigo'] ? $curso['codigo'] . ' · ' : '') . $curso['nombre']; ?>
                    <option value="<?= $curso['id'] ?>" <?= $selected('curso_id', $curso['id']) ?>>
                        <?= htmlspecialchars($cursoNombre) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-grid thirds">
        <label>
            Estado físico
            <select name="estado_fisico_id" required>
                <?php foreach ($data['catalogos']['estados_fisicos'] as $estado): ?>
                    <option
                        value="<?= $estado['id'] ?>"
                        <?= $selected('estado_fisico_id', $estado['id']) ?>
                    >
                        <?= htmlspecialchars($estado['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Disponibilidad
            <select name="estado_publicacion_id" required>
                <?php foreach ($data['catalogos']['estados_publicacion'] as $estado): ?>
                    <option
                        value="<?= $estado['id'] ?>"
                        <?= $selected('estado_publicacion_id', $estado['id']) ?>
                    >
                        <?= htmlspecialchars($estado['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Modalidad
            <select name="modalidad_id" required>
                <?php foreach ($data['catalogos']['modalidades'] as $modalidad): ?>
                    <option
                        value="<?= $modalidad['id'] ?>"
                        <?= $selected('modalidad_id', $modalidad['id']) ?>
                    >
                        <?= htmlspecialchars($modalidad['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-grid">
        <label>
            Edición
            <input
                name="edicion"
                value="<?= htmlspecialchars($publicacion['edicion'] ?? '') ?>"
                placeholder="Ej. 3.ª edición"
            >
        </label>
        <label>
            Valor en créditos
            <input
                type="number"
                step="0.01"
                min="0"
                name="valor_creditos"
                value="<?= htmlspecialchars($publicacion['valor_creditos'] ?? '0') ?>"
            >
        </label>
    </div>

    <label>
        Observaciones
        <textarea name="observaciones" rows="3"><?=
            htmlspecialchars($publicacion['observaciones'] ?? '')
        ?></textarea>
    </label>

    <label>
        Imagen principal
        <?php if ($editing): ?><small>(opcional para conservar la actual)</small><?php endif; ?>
        <input
            type="file"
            name="imagen"
            accept="image/jpeg,image/png,image/webp"
            <?= $editing ? '' : 'required' ?>
        >
    </label>

    <div class="form-actions">
        <a class="btn ghost" href="<?= BASE_URL ?>/publicaciones/mis-publicaciones">Cancelar</a>
        <button class="btn primary"><?= $editing ? 'Guardar cambios' : 'Publicar material' ?></button>
    </div>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
