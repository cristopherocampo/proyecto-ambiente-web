<?php require_once 'app/views/layouts/header.php'; ?>

<div class="page-header">
    <div>
        <h2>Historial de intercambios</h2>
        <p>Consulta y administra los intercambios aceptados.</p>
    </div>

    <a
        href="<?= BASE_URL ?>/solicitud/index"
        class="btn btn-secondary"
    >
        Ver solicitudes
    </a>
</div>

<div class="glass-card intercambios-card">
    <h3>Mis intercambios</h3>

    <div id="intercambiosLoader" class="loader-container">
        <div class="spinner"></div>
    </div>

    <div class="table-responsive">
        <table class="table hidden" id="intercambiosTable">
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Solicitante</th>
                    <th>Propietario</th>
                    <th>Estado</th>
                    <th>Fecha de inicio</th>
                    <th>Fecha de finalización</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody id="intercambiosTbody">
            </tbody>
        </table>
    </div>
</div>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>

<script src="<?= BASE_URL ?>/public/js/intercambios.js"></script>

<?php require_once 'app/views/layouts/footer.php'; ?>