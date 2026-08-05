<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="screen-heading">
    <div>
        <h1>Explorar materiales</h1>
        <p>Encuentra libros y apuntes de tu comunidad académica.</p>
    </div>
    <a class="btn primary" href="<?= BASE_URL ?>/publicaciones/crear">＋ Publicar material</a>
</section>

<?php if (!empty($data['flash'])): ?>
    <div class="alert <?= $data['flash']['type'] === 'success' ? 'success' : 'error' ?>">
        <?= htmlspecialchars($data['flash']['message']) ?>
    </div>
<?php endif; ?>

<div class="catalog-layout">
    <form class="filter-panel" method="get" action="<?= BASE_URL ?>/catalogo/index">
        <div class="filter-title">
            <strong>Filtros</strong>
            <a href="<?= BASE_URL ?>/catalogo/index">Limpiar</a>
        </div>

        <label>
            Buscar
            <input
                name="q"
                value="<?= htmlspecialchars($data['filters']['q'] ?? '') ?>"
                placeholder="Título o autor"
            >
        </label>

        <label>
            Categoría
            <select name="categoria_id">
                <option value="">Todas las categorías</option>
                <?php foreach ($data['catalogos']['categorias'] as $categoria): ?>
                    <option
                        value="<?= $categoria['id'] ?>"
                        <?= ($data['filters']['categoria_id'] ?? '') == $categoria['id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Carrera
            <select name="carrera_id">
                <option value="">Todas las carreras</option>
                <?php foreach ($data['catalogos']['carreras'] as $carrera): ?>
                    <option
                        value="<?= $carrera['id'] ?>"
                        <?= ($data['filters']['carrera_id'] ?? '') == $carrera['id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($carrera['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Curso
            <select name="curso_id">
                <option value="">Todos los cursos</option>
                <?php foreach ($data['catalogos']['cursos'] as $curso): ?>
                    <?php $cursoNombre = ($curso['codigo'] ? $curso['codigo'] . ' · ' : '') . $curso['nombre']; ?>
                    <option
                        value="<?= $curso['id'] ?>"
                        <?= ($data['filters']['curso_id'] ?? '') == $curso['id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($cursoNombre) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Disponibilidad
            <select name="estado_publicacion_id">
                <option value="">Cualquier estado</option>
                <?php foreach ($data['catalogos']['estados_publicacion'] as $estado): ?>
                    <option
                        value="<?= $estado['id'] ?>"
                        <?= ($data['filters']['estado_publicacion_id'] ?? '') == $estado['id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($estado['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <button class="btn primary">Aplicar filtros</button>
    </form>

    <section class="catalog-results">
        <div class="results-toolbar">
            <div>Mostrando <strong><?= count($data['publicaciones']) ?></strong> materiales</div>
            <span>Más recientes⌄</span>
        </div>

        <div class="catalog-grid">
            <?php foreach ($data['publicaciones'] as $publicacion): ?>
                <article class="material-card">
                    <a
                        class="cover"
                        href="<?= BASE_URL ?>/publicaciones/detalle/<?= $publicacion['id'] ?>"
                    >
                        <?php if ($publicacion['imagen']): ?>
                            <img
                                src="<?= BASE_URL . '/' . htmlspecialchars($publicacion['imagen']) ?>"
                                alt="Portada de <?= htmlspecialchars($publicacion['titulo']) ?>"
                            >
                        <?php else: ?>
                            <span>📚</span>
                        <?php endif; ?>
                        <span class="availability">
                            <?= htmlspecialchars(ucfirst(strtolower($publicacion['estado_publicacion']))) ?>
                        </span>
                    </a>

                    <div class="material-body">
                        <span class="material-type">
                            <?= htmlspecialchars($publicacion['tipo_material']) ?>
                        </span>
                        <h2><?= htmlspecialchars($publicacion['titulo']) ?></h2>
                        <p><?= htmlspecialchars($publicacion['autor'] ?? 'Autor no indicado') ?></p>
                        <small>
                            <?= htmlspecialchars($publicacion['categoria'] ?? 'Sin categoría') ?>
                            · <?= htmlspecialchars($publicacion['estado_fisico']) ?>
                        </small>
                        <div class="card-bottom">
                            <strong>
                                <?= $publicacion['valor_creditos'] > 0
                                    ? htmlspecialchars($publicacion['valor_creditos']) . ' créditos'
                                    : 'Intercambio' ?>
                            </strong>
                            <a href="<?= BASE_URL ?>/publicaciones/detalle/<?= $publicacion['id'] ?>">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (!$data['publicaciones']): ?>
            <div class="empty card">
                <div>⌕</div>
                <h2>No encontramos materiales</h2>
                <p>Prueba cambiando o limpiando los filtros.</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
