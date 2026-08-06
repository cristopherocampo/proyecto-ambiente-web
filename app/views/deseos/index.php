<?php
$deseos = $data["deseos"] ?? [];
$coincidencias = $data["coincidencias"] ?? [];
$flash = $data["flash"] ?? null;

require_once "app/views/layouts/header.php";
?>

<div class="page-header">
    <div>
        <h2>Lista de materiales deseados</h2>
        <p>
            Guarda los materiales que te interesan y consulta
            publicaciones disponibles que coincidan con ellos.
        </p>
    </div>

    <a
        href="<?= BASE_URL ?>/catalogo/index"
        class="btn btn-secondary"
    >
        Explorar materiales
    </a>
</div>

<?php if ($flash): ?>
    <div
        class="alert <?= ($flash["type"] ?? "") === "success"
            ? "alert-success"
            : "alert-danger" ?>"
    >
        <?= htmlspecialchars($flash["message"] ?? "") ?>
    </div>
<?php endif; ?>

<div class="glass-card intercambios-card">
    <h3>Mis materiales deseados</h3>

    <?php if (empty($deseos)): ?>

        <p class="text-muted">
            Todavía no has agregado materiales a tu lista de deseos.
            Podés agregarlos desde el detalle de una publicación.
        </p>

    <?php else: ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Autor</th>
                        <th>Tipo</th>
                        <th>Curso</th>
                        <th>Categoría</th>
                        <th>Fecha agregada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($deseos as $deseo): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?= htmlspecialchars(
                                        $deseo["titulo"]
                                    ) ?>
                                </strong>

                                <?php if (
                                    !empty($deseo["edicion"])
                                ): ?>
                                    <small>
                                        Edición:
                                        <?= htmlspecialchars(
                                            $deseo["edicion"]
                                        ) ?>
                                    </small>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $deseo["autor"]
                                    ?? "Autor no indicado"
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $deseo["tipo_material"]
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $deseo["curso"] ?? "—"
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $deseo["categoria"] ?? "—"
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    date(
                                        "d/m/Y",
                                        strtotime(
                                            $deseo["fecha_registro"]
                                        )
                                    )
                                ) ?>
                            </td>

                            <td class="actions">
                                <form
                                    method="post"
                                    action="<?= BASE_URL ?>/deseo/eliminar/<?= (int) $deseo["id"] ?>"
                                    onsubmit="return confirm('¿Deseas eliminar este material de tu lista?');"
                                >
                                    <input
                                        type="hidden"
                                        name="csrf"
                                        value="<?= htmlspecialchars(
                                            $data["csrf"]
                                        ) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                    >
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</div>

<div
    class="glass-card intercambios-card"
    style="margin-top: 25px;"
>
    <h3>Coincidencias disponibles</h3>

    <?php if (empty($coincidencias)): ?>

        <p class="text-muted">
            Por el momento no existen publicaciones disponibles que
            coincidan con tu lista de materiales deseados.
        </p>

    <?php else: ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Autor</th>
                        <th>Propietario</th>
                        <th>Estado físico</th>
                        <th>Modalidad</th>
                        <th>Créditos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach (
                        $coincidencias as $coincidencia
                    ): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?= htmlspecialchars(
                                        $coincidencia["titulo"]
                                    ) ?>
                                </strong>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $coincidencia["autor"]
                                    ?? "Autor no indicado"
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $coincidencia["propietario"]
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $coincidencia[
                                        "estado_fisico"
                                    ]
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $coincidencia["modalidad"]
                                ) ?>
                            </td>

                            <td>
                                <?php if (
                                    (float) $coincidencia[
                                        "valor_creditos"
                                    ] > 0
                                ): ?>
                                    <?= htmlspecialchars(
                                        $coincidencia[
                                            "valor_creditos"
                                        ]
                                    ) ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>

                            <td class="actions">
                                <a
                                    href="<?= BASE_URL ?>/publicaciones/detalle/<?= (int) $coincidencia["publicacion_id"] ?>"
                                    class="btn btn-primary btn-sm"
                                >
                                    Ver material
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</div>

<?php require_once "app/views/layouts/footer.php"; ?>