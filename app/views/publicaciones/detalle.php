<?php
$publicacion = $data['publicacion'];
require __DIR__ . '/../layouts/header.php';
?>

<div class="breadcrumbs">
    <a href="<?= BASE_URL ?>/catalogo/index">Explorar</a>
    <span>›</span>
    <span><?= htmlspecialchars($publicacion['categoria'] ?? 'Material') ?></span>
    <span>›</span>
    <strong><?= htmlspecialchars($publicacion['titulo']) ?></strong>
</div>

<section class="detail">
    <div class="detail-gallery card">
        <div class="detail-cover">
            <?php if ($publicacion['imagen']): ?>
                <img
                    src="<?= BASE_URL . '/' . htmlspecialchars($publicacion['imagen']) ?>"
                    alt="<?= htmlspecialchars($publicacion['titulo']) ?>"
                >
            <?php else: ?>
                <span>📚</span>
            <?php endif; ?>
        </div>

        <div class="thumb-row">
            <span class="active">Vista principal</span>
            <span>Estado: <?= htmlspecialchars($publicacion['estado_fisico']) ?></span>
        </div>
    </div>

    <div class="detail-body">
        <div class="badges">
            <span><?= htmlspecialchars($publicacion['tipo_material']) ?></span>
            <span><?= htmlspecialchars($publicacion['categoria'] ?? 'Material académico') ?></span>
        </div>

        <h1><?= htmlspecialchars($publicacion['titulo']) ?></h1>
        <h3><?= htmlspecialchars($publicacion['autor'] ?? 'Autor no indicado') ?></h3>

        <div class="detail-price">
            <?= $publicacion['valor_creditos'] > 0
                ? htmlspecialchars($publicacion['valor_creditos']) . ' créditos'
                : htmlspecialchars($publicacion['modalidad']) ?>
        </div>

        <?php if ((int) $publicacion['propietario_id'] === (int) $_SESSION['user_id']): ?>
            <div class="alert info">Esta publicación es tuya; no puedes solicitarla.</div>
            <a
                class="btn primary wide-btn"
                href="<?= BASE_URL ?>/publicaciones/editar/<?= $publicacion['id'] ?>"
            >
                Editar publicación
            </a>
        <?php else: ?>
            <form
                method="post"
                action="<?= BASE_URL ?>/publicaciones/solicitar/<?= $publicacion['id'] ?>"
            >
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($data['csrf']) ?>">
                <button class="btn primary wide-btn">Solicitar intercambio</button>
            </form>
        <?php endif; ?>

        <div class="owner-card">
            <span class="avatar">
                <?= htmlspecialchars(mb_strtoupper(mb_substr($publicacion['propietario'], 0, 1))) ?>
            </span>
            <div>
                <small>Publicado por</small>
                <strong><?= htmlspecialchars($publicacion['propietario']) ?></strong>
                <span>Miembro de la comunidad BookCycle</span>
            </div>
        </div>

        <div class="description-block">
            <h2>Descripción del material</h2>
            <p><?= nl2br(htmlspecialchars($publicacion['descripcion'])) ?></p>
        </div>

        <dl>
            <div>
                <dt>Curso</dt>
                <dd><?= htmlspecialchars($publicacion['curso'] ?? '—') ?></dd>
            </div>
            <div>
                <dt>Carrera</dt>
                <dd><?= htmlspecialchars($publicacion['carrera'] ?? '—') ?></dd>
            </div>
            <div>
                <dt>Edición</dt>
                <dd><?= htmlspecialchars($publicacion['edicion'] ?: '—') ?></dd>
            </div>
            <div>
                <dt>Modalidad</dt>
                <dd><?= htmlspecialchars($publicacion['modalidad']) ?></dd>
            </div>
        </dl>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
