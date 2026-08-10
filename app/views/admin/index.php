<?php

$data["titulo"] = "Administración | BookCycle";

require __DIR__ . "/../layouts/header.php";
?>

<div class="screen-heading">
    <div>
        <h1>Administración</h1>
        <p>
            Resumen y gestión general de BookCycle.
        </p>
    </div>
</div>

<?php if (!empty($data["flash"])): ?>
    <div class="alert <?= htmlspecialchars($data["flash"]["type"]) ?>">
        <?= htmlspecialchars($data["flash"]["message"]) ?>
    </div>
<?php endif; ?>

<div class="admin-summary">

    <div class="card admin-stat">
        <strong><?= (int) $data["resumen"]["usuarios"] ?></strong>
        <span>Usuarios</span>
    </div>

    <div class="card admin-stat">
        <strong><?= (int) $data["resumen"]["publicaciones"] ?></strong>
        <span>Publicaciones</span>
    </div>

    <div class="card admin-stat">
        <strong><?= (int) $data["resumen"]["solicitudes"] ?></strong>
        <span>Solicitudes</span>
    </div>

    <div class="card admin-stat">
        <strong><?= (int) $data["resumen"]["intercambios"] ?></strong>
        <span>Intercambios</span>
    </div>

</div>

<section class="admin-section">

    <div class="page-head">
        <div>
            <h2>Usuarios</h2>
            <p>Usuarios registrados en la plataforma.</p>
        </div>
    </div>

    <div class="table-wrap card">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($data["usuarios"] as $usuario): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                $usuario["nombre"] . " " .
                                $usuario["apellidos"]
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($usuario["correo"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($usuario["estado"]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</section>

<section class="admin-section">

    <div class="page-head">
        <div>
            <h2>Publicaciones</h2>
            <p>Materiales publicados por los estudiantes.</p>
        </div>
    </div>

    <div class="table-wrap card">
        <table class="table">
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Propietario</th>
                    <th>Modalidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($data["publicaciones"] as $publicacion): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($publicacion["titulo"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $publicacion["propietario"]
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $publicacion["modalidad"]
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $publicacion["estado"]
                            ) ?>
                        </td>

                        <td class="actions">
                            <?php if ($publicacion["estado"] !== "INACTIVA"): ?>
                                <form
                                    method="post"
                                    action="<?= BASE_URL ?>/admin/desactivar/<?= $publicacion["id"] ?>"
                                    onsubmit="return confirm('¿Desactivar esta publicación?')"
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
                                        class="link-danger"
                                    >
                                        Desactivar
                                    </button>
                                </form>
                            <?php else: ?>
                                Inactiva
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</section>

<section class="admin-section">

    <div class="page-head">
        <div>
            <h2>Reportes</h2>
            <p>Reportes realizados dentro de la plataforma.</p>
        </div>
    </div>

    <div class="table-wrap card">
        <table class="table">
            <thead>
                <tr>
                    <th>Reportante</th>
                    <th>Motivo</th>
                    <th>Detalle</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($data["reportes"])): ?>
                    <tr>
                        <td colspan="5" class="empty">
                            No hay reportes registrados.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data["reportes"] as $reporte): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                $reporte["reportante"]
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($reporte["motivo"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $reporte["detalle"] ?? ""
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($reporte["estado"]) ?>
                        </td>

                        <td class="actions">
                            <?php if ($reporte["estado"] !== "RESUELTO"): ?>
                                <form
                                    method="post"
                                    action="<?= BASE_URL ?>/admin/resolver/<?= $reporte["id"] ?>"
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
                                        class="btn primary"
                                    >
                                        Resolver
                                    </button>
                                </form>
                            <?php else: ?>
                                Resuelto
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>