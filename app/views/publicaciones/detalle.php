<?php
$publicacion = $data['publicacion'];

$esPropietario =
    (int) $publicacion['propietario_id'] ===
    (int) $_SESSION['user_id'];

$estaDisponible =
    ($publicacion['estado_publicacion'] ?? '') === 'DISPONIBLE';

require __DIR__ . '/../layouts/header.php';
?>

<div class="breadcrumbs">
    <a href="<?= BASE_URL ?>/catalogo/index">
        Explorar
    </a>

    <span>›</span>

    <span>
        <?= htmlspecialchars(
            $publicacion['categoria'] ?? 'Material'
        ) ?>
    </span>

    <span>›</span>

    <strong>
        <?= htmlspecialchars($publicacion['titulo']) ?>
    </strong>
</div>

<section class="detail">

    <div class="detail-gallery card">

        <div class="detail-cover">
            <?php if (!empty($publicacion['imagen'])): ?>

                <img
                    src="<?= BASE_URL . '/' . htmlspecialchars(
                        $publicacion['imagen']
                    ) ?>"
                    alt="<?= htmlspecialchars(
                        $publicacion['titulo']
                    ) ?>"
                >

            <?php else: ?>

                <span>📚</span>

            <?php endif; ?>
        </div>

        <div class="thumb-row">

            <span class="active">
                Vista principal
            </span>

            <span>
                Estado:
                <?= htmlspecialchars(
                    $publicacion['estado_fisico']
                ) ?>
            </span>

        </div>

    </div>

    <div class="detail-body">

        <div class="badges">

            <span>
                <?= htmlspecialchars(
                    $publicacion['tipo_material']
                ) ?>
            </span>

            <span>
                <?= htmlspecialchars(
                    $publicacion['categoria']
                    ?? 'Material académico'
                ) ?>
            </span>

            <?php if (!$estaDisponible): ?>
                <span>
                    <?= htmlspecialchars(
                        $publicacion['estado_publicacion']
                    ) ?>
                </span>
            <?php endif; ?>

        </div>

        <h1>
            <?= htmlspecialchars($publicacion['titulo']) ?>
        </h1>

        <h3>
            <?= htmlspecialchars(
                $publicacion['autor']
                ?? 'Autor no indicado'
            ) ?>
        </h3>

        <div class="detail-price">
            <?php if (
                (float) $publicacion['valor_creditos'] > 0
            ): ?>

                <?= htmlspecialchars(
                    $publicacion['valor_creditos']
                ) ?>
                créditos

            <?php else: ?>

                <?= htmlspecialchars(
                    $publicacion['modalidad']
                ) ?>

            <?php endif; ?>
        </div>

        <?php if (!empty($data['flash'])): ?>

            <div class="alert <?= htmlspecialchars(
                $data['flash']['type'] ?? 'info'
            ) ?>">
                <?= htmlspecialchars(
                    $data['flash']['message'] ?? ''
                ) ?>
            </div>

        <?php endif; ?>

        <?php if ($esPropietario): ?>

            <div class="alert info">
                Esta publicación es tuya; no puedes solicitarla.
            </div>

            <a
                class="btn primary wide-btn"
                href="<?= BASE_URL ?>/publicaciones/editar/<?= (int) $publicacion['id'] ?>"
            >
                Editar publicación
            </a>

        <?php elseif ($estaDisponible): ?>

            <form
                method="post"
                action="<?= BASE_URL ?>/publicaciones/solicitar/<?= (int) $publicacion['id'] ?>"
            >
                <input
                    type="hidden"
                    name="csrf"
                    value="<?= htmlspecialchars(
                        $data['csrf']
                    ) ?>"
                >

                <button
                    type="submit"
                    class="btn primary wide-btn"
                >
                    Solicitar intercambio
                </button>
            </form>

        <?php else: ?>

            <?php if (
                $publicacion['estado_publicacion']
                === 'INTERCAMBIADA'
            ): ?>

                <div class="alert info">
                    Este material ya fue intercambiado y no está
                    disponible para nuevas solicitudes.
                </div>

            <?php elseif (
                $publicacion['estado_publicacion']
                === 'RESERVADA'
            ): ?>

                <div class="alert info">
                    Este material se encuentra reservado y no admite
                    nuevas solicitudes.
                </div>

            <?php elseif (
                $publicacion['estado_publicacion']
                === 'BORRADOR'
            ): ?>

                <div class="alert info">
                    Esta publicación todavía está en borrador.
                </div>

            <?php else: ?>

                <div class="alert info">
                    Este material no está disponible actualmente.
                </div>

            <?php endif; ?>

        <?php endif; ?>

        <div class="owner-card">

            <span class="avatar">
                <?= htmlspecialchars(
                    mb_strtoupper(
                        mb_substr(
                            $publicacion['propietario'],
                            0,
                            1
                        )
                    )
                ) ?>
            </span>

            <div>

                <small>
                    Publicado por
                </small>

                <strong>
                    <?= htmlspecialchars(
                        $publicacion['propietario']
                    ) ?>
                </strong>

                <span>
                    Miembro de la comunidad BookCycle
                </span>

            </div>

        </div>

        <div class="description-block">

            <h2>
                Descripción del material
            </h2>

            <p>
                <?= nl2br(
                    htmlspecialchars(
                        $publicacion['descripcion']
                    )
                ) ?>
            </p>

        </div>

        <dl>

            <div>
                <dt>Curso</dt>

                <dd>
                    <?= htmlspecialchars(
                        $publicacion['curso'] ?? '—'
                    ) ?>
                </dd>
            </div>

            <div>
                <dt>Carrera</dt>

                <dd>
                    <?= htmlspecialchars(
                        $publicacion['carrera'] ?? '—'
                    ) ?>
                </dd>
            </div>

            <div>
                <dt>Edición</dt>

                <dd>
                    <?= htmlspecialchars(
                        $publicacion['edicion'] ?: '—'
                    ) ?>
                </dd>
            </div>

            <div>
                <dt>Modalidad</dt>

                <dd>
                    <?= htmlspecialchars(
                        $publicacion['modalidad']
                    ) ?>
                </dd>
            </div>

        </dl>

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>