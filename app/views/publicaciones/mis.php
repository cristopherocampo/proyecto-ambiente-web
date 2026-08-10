<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="page-head">
    <div>
        <span class="eyebrow">GESTIÓN</span>
        <h1>Mis publicaciones</h1>
        <p>Administra tus materiales y su disponibilidad.</p>
    </div>
    <a class="btn primary" href="<?= BASE_URL ?>/publicaciones/crear">+ Nueva publicación</a>
</section>

<?php if (!empty($data['flash'])): ?>
    <div class="alert <?= $data['flash']['type'] === 'success' ? 'success' : 'error' ?>">
        <?= htmlspecialchars($data['flash']['message']) ?>
    </div>
<?php endif; ?>

<div class="table-wrap card">
    <table class="table">
        <thead>
            <tr>
                <th>Material</th>
                <th>Estado</th>
                <th>Condición</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['publicaciones'] as $publicacion): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($publicacion['titulo']) ?></strong>
                        <small><?= htmlspecialchars($publicacion['autor'] ?? '') ?></small>
                    </td>
                    <td>
                        <form
                            class="inline"
                            method="post"
                            action="<?= BASE_URL ?>/publicaciones/disponibilidad/<?= $publicacion['id'] ?>"
                        >
                            <input
                                type="hidden"
                                name="csrf"
                                value="<?= htmlspecialchars($data['csrf']) ?>"
                            >
                            <select name="estado_publicacion_id" onchange="this.form.submit()">
                                <?php foreach (
                                    [
                                        1 => 'BORRADOR',
                                        2 => 'DISPONIBLE',
                                        3 => 'RESERVADA',
                                        4 => 'INTERCAMBIADA',
                                        5 => 'INACTIVA',
                                    ] as $estadoId => $estadoNombre
                                ): ?>
                                    <option
                                        value="<?= $estadoId ?>"
                                        <?= $publicacion['estado_publicacion_id'] == $estadoId
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        <?= $estadoNombre ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($publicacion['estado_fisico']) ?></td>
                    <td class="actions">
                        <a href="<?= BASE_URL ?>/publicaciones/detalle/<?= $publicacion['id'] ?>">Ver</a>
                        <a href="<?= BASE_URL ?>/publicaciones/editar/<?= $publicacion['id'] ?>">Editar</a>
                        <form
                            method="post"
                            action="<?= BASE_URL ?>/publicaciones/eliminar/<?= $publicacion['id'] ?>"
                            onsubmit="return confirm('¿Eliminar esta publicación?')"
                        >
                            <input
                                type="hidden"
                                name="csrf"
                                value="<?= htmlspecialchars($data['csrf']) ?>"
                            >
                            <button class="link-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (!$data['publicaciones']): ?>
        <div class="empty"><p>Aún no tienes publicaciones.</p></div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
