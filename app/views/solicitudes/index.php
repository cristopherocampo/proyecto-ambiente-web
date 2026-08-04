<?php require_once 'app/views/layouts/header.php'; ?>

<div class="page-header">
    <div>
        <h2>Solicitudes de intercambio</h2>
        <p>Administra las solicitudes enviadas y recibidas.</p>
    </div>
</div>

<div class="solicitudes-tabs">
    <button
        type="button"
        class="btn btn-primary"
        id="btnEnviadas"
    >
        Solicitudes enviadas
    </button>

    <button
        type="button"
        class="btn btn-secondary"
        id="btnRecibidas"
    >
        Solicitudes recibidas
    </button>
</div>

<div id="seccionEnviadas" class="solicitudes-seccion">
    <div class="glass-card">
        <h3>Solicitudes enviadas</h3>

        <div id="enviadasLoader" class="loader-container">
            <div class="spinner"></div>
        </div>

        <div class="table-responsive">
            <table class="table hidden" id="enviadasTable">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Propietario</th>
                        <th>Mensaje</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="enviadasTbody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div
    id="seccionRecibidas"
    class="solicitudes-seccion hidden"
>
    <div class="glass-card">
        <h3>Solicitudes recibidas</h3>

        <div id="recibidasLoader" class="loader-container">
            <div class="spinner"></div>
        </div>

        <div class="table-responsive">
            <table class="table hidden" id="recibidasTable">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Solicitante</th>
                        <th>Mensaje</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="recibidasTbody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-overlay" id="rechazoModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Rechazar solicitud</h3>

            <button
                type="button"
                class="modal-close"
                id="closeRechazoModal"
            >
                &times;
            </button>
        </div>

        <form id="rechazoForm">
            <input
                type="hidden"
                id="solicitudRechazoId"
            >

            <div class="form-group">
                <label for="motivoRechazo">
                    Motivo del rechazo
                </label>

                <textarea
                    id="motivoRechazo"
                    class="form-control"
                    rows="4"
                    required
                    placeholder="Escribí el motivo del rechazo"
                ></textarea>
            </div>

            <button
                type="submit"
                class="btn btn-danger btn-block"
                id="btnConfirmarRechazo"
            >
                Rechazar solicitud
            </button>
        </form>
    </div>
</div>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>

<script src="<?= BASE_URL ?>/public/js/solicitudes.js"></script>

<?php require_once 'app/views/layouts/footer.php'; ?>