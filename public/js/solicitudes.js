document.addEventListener('DOMContentLoaded', () => {

    console.log('solicitudes.js loaded');

    loadEnviadas();
    loadRecibidas();

    const btnEnviadas = document.getElementById('btnEnviadas');
    const btnRecibidas = document.getElementById('btnRecibidas');
    const seccionEnviadas = document.getElementById('seccionEnviadas');
    const seccionRecibidas = document.getElementById('seccionRecibidas');
    const rechazoModal = document.getElementById('rechazoModal');
    const closeRechazoModal = document.getElementById('closeRechazoModal');
    const rechazoForm = document.getElementById('rechazoForm');

    btnEnviadas.addEventListener('click', () => {
        seccionEnviadas.classList.remove('hidden');
        seccionRecibidas.classList.add('hidden');

        btnEnviadas.classList.add('btn-primary');
        btnEnviadas.classList.remove('btn-secondary');

        btnRecibidas.classList.add('btn-secondary');
        btnRecibidas.classList.remove('btn-primary');
    });

    btnRecibidas.addEventListener('click', () => {
        seccionRecibidas.classList.remove('hidden');
        seccionEnviadas.classList.add('hidden');

        btnRecibidas.classList.add('btn-primary');
        btnRecibidas.classList.remove('btn-secondary');

        btnEnviadas.classList.add('btn-secondary');
        btnEnviadas.classList.remove('btn-primary');
    });

    closeRechazoModal.addEventListener('click', () => {
        rechazoModal.classList.remove('active');
        rechazoForm.reset();
    });

    rechazoForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = document.getElementById('solicitudRechazoId').value;
        const motivo = document.getElementById('motivoRechazo').value;

        try {
            const response = await fetch(
                `${BASE_URL}/solicitud/apiRechazar/${id}`,
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        motivo: motivo
                    })
                }
            );

            const result = await response.json();

            if (result.success) {
                rechazoModal.classList.remove('active');
                rechazoForm.reset();

                Swal.fire(
                    'Éxito',
                    result.message,
                    'success'
                );

                loadRecibidas();

            } else {
                Swal.fire(
                    'Error',
                    result.message,
                    'error'
                );
            }

        } catch (error) {
            Swal.fire(
                'Error',
                'Ocurrió un error al rechazar la solicitud.',
                'error'
            );
        }
    });
});


async function loadEnviadas() {
    const loader = document.getElementById('enviadasLoader');
    const table = document.getElementById('enviadasTable');
    const tbody = document.getElementById('enviadasTbody');

    loader.classList.remove('hidden');
    table.classList.add('hidden');
    tbody.innerHTML = '';

    try {
        const response = await fetch(
            `${BASE_URL}/solicitud/apiEnviadas`
        );

        const solicitudes = await response.json();

        if (solicitudes.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No hay solicitudes enviadas.
                    </td>
                </tr>
            `;
        } else {
            solicitudes.forEach(solicitud => {
                const tr = document.createElement('tr');

                let acciones = '<span class="text-muted">—</span>';

                if (parseInt(solicitud.estado_solicitud_id) === 1) {
                    acciones = `
                        <button
                            class="btn btn-danger btn-sm"
                            onclick="cancelarSolicitud(${solicitud.id})"
                        >
                            Cancelar
                        </button>
                    `;
                }

                tr.innerHTML = `
                    <td>
                        ${solicitud.titulo}
                        <br>
                        <small>
                            ${solicitud.autor ? solicitud.autor : 'Autor no indicado'}
                        </small>
                    </td>

                    <td>
                        ${solicitud.material_ofrecido
                        ? solicitud.material_ofrecido
                        : 'Sin material ofrecido'}
                    </td>

                    <td>
                        ${solicitud.propietario_nombre}
                        ${solicitud.propietario_apellidos}
                    </td>

                    <td>
                        ${solicitud.mensaje ? solicitud.mensaje : 'Sin mensaje'}
                    </td>

                    <td>${solicitud.estado}</td>

                    <td>${solicitud.fecha_solicitud}</td>

                    <td class="actions">
                        ${acciones}
                    </td>
                `;

                tbody.appendChild(tr);
            });
        }

    } catch (error) {
        Swal.fire(
            'Error',
            'No se lograron cargar las solicitudes enviadas.',
            'error'
        );

    } finally {
        loader.classList.add('hidden');
        table.classList.remove('hidden');
    }
}


async function loadRecibidas() {
    const loader = document.getElementById('recibidasLoader');
    const table = document.getElementById('recibidasTable');
    const tbody = document.getElementById('recibidasTbody');

    loader.classList.remove('hidden');
    table.classList.add('hidden');
    tbody.innerHTML = '';

    try {
        const response = await fetch(
            `${BASE_URL}/solicitud/apiRecibidas`
        );

        const solicitudes = await response.json();

        if (solicitudes.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No hay solicitudes recibidas.
                    </td>
                </tr>
            `;
        } else {
            solicitudes.forEach(solicitud => {
                const tr = document.createElement('tr');

                let acciones = '<span class="text-muted">—</span>';

                if (parseInt(solicitud.estado_solicitud_id) === 1) {
                    acciones = `
                        <button
                            class="btn btn-primary btn-sm"
                            onclick="aceptarSolicitud(${solicitud.id})"
                        >
                            Aceptar
                        </button>

                        <button
                            class="btn btn-danger btn-sm"
                            onclick="abrirRechazo(${solicitud.id})"
                        >
                            Rechazar
                        </button>
                    `;
                }

                tr.innerHTML = `
                    <td>
                        ${solicitud.titulo}
                        <br>
                        <small>
                            ${solicitud.autor ? solicitud.autor : 'Autor no indicado'}
                        </small>
                    </td>

                    <td>
                        ${solicitud.material_ofrecido
                        ? solicitud.material_ofrecido
                        : 'Sin material ofrecido'}
                    </td>

                    <td>
                        ${solicitud.solicitante_nombre}
                        ${solicitud.solicitante_apellidos}
                    </td>

                    <td>
                        ${solicitud.mensaje ? solicitud.mensaje : 'Sin mensaje'}
                    </td>

                    <td>${solicitud.estado}</td>

                    <td>${solicitud.fecha_solicitud}</td>

                    <td class="actions">
                        ${acciones}
                    </td>
                `;

                tbody.appendChild(tr);
            });
        }

    } catch (error) {
        Swal.fire(
            'Error',
            'No se lograron cargar las solicitudes recibidas.',
            'error'
        );

    } finally {
        loader.classList.add('hidden');
        table.classList.remove('hidden');
    }
}


function aceptarSolicitud(id) {
    Swal.fire({
        title: '¿Aceptar solicitud?',
        text: 'La solicitud cambiará al estado aceptada.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, aceptar',
        cancelButtonText: 'Cancelar'

    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(
                    `${BASE_URL}/solicitud/apiAceptar/${id}`,
                    {
                        method: 'POST'
                    }
                );

                const resData = await response.json();

                if (resData.success) {
                    Swal.fire(
                        'Éxito',
                        resData.message,
                        'success'
                    );

                    loadRecibidas();
                    loadEnviadas();

                } else {
                    Swal.fire(
                        'Error',
                        resData.message,
                        'error'
                    );
                }

            } catch (error) {
                Swal.fire(
                    'Error',
                    'Ocurrió un error al aceptar la solicitud.',
                    'error'
                );
            }
        }
    });
}


function abrirRechazo(id) {
    document.getElementById('solicitudRechazoId').value = id;
    document.getElementById('motivoRechazo').value = '';
    document.getElementById('rechazoModal').classList.add('active');
}


function cancelarSolicitud(id) {
    Swal.fire({
        title: '¿Cancelar solicitud?',
        text: 'La solicitud pendiente será cancelada.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Volver'

    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(
                    `${BASE_URL}/solicitud/apiCancelar/${id}`,
                    {
                        method: 'POST'
                    }
                );

                const resData = await response.json();

                if (resData.success) {
                    Swal.fire(
                        'Éxito',
                        resData.message,
                        'success'
                    );

                    loadEnviadas();

                } else {
                    Swal.fire(
                        'Error',
                        resData.message,
                        'error'
                    );
                }

            } catch (error) {
                Swal.fire(
                    'Error',
                    'Ocurrió un error al cancelar la solicitud.',
                    'error'
                );
            }
        }
    });
}