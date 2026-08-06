document.addEventListener('DOMContentLoaded', () => {

    console.log('intercambios.js loaded');

    loadIntercambios();
});


async function loadIntercambios() {
    const loader = document.getElementById('intercambiosLoader');
    const table = document.getElementById('intercambiosTable');
    const tbody = document.getElementById('intercambiosTbody');

    loader.classList.remove('hidden');
    table.classList.add('hidden');
    tbody.innerHTML = '';

    try {
        const response = await fetch(
            `${BASE_URL}/intercambio/apiList`
        );

        const intercambios = await response.json();

        if (intercambios.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No hay intercambios registrados.
                    </td>
                </tr>
            `;
        } else {
            intercambios.forEach(intercambio => {
                const tr = document.createElement('tr');

                let acciones = '';

                if (
                    parseInt(
                        intercambio.estado_intercambio_id
                    ) === 1
                ) {
                    acciones = `
                        <button
                            class="btn btn-primary btn-sm"
                            onclick="completarIntercambio(${intercambio.id})"
                        >
                            Completar
                        </button>
                    `;
                }

                if (
                    parseInt(
                        intercambio.estado_intercambio_id
                    ) === 4
                ) {
                    if (
                        parseInt(
                            intercambio.ya_valorado
                        ) === 1
                    ) {
                        acciones = `
                            <span class="text-muted">
                                Valorado
                            </span>
                        `;
                    } else {
                        acciones = `
                            <a
                                class="btn btn-secondary btn-sm"
                                href="${BASE_URL}/valoracion/crear/${intercambio.id}"
                            >
                                Valorar
                            </a>
                        `;
                    }
                }

                const fechaFinalizacion =
                    intercambio.fecha_finalizacion
                        ? intercambio.fecha_finalizacion
                        : 'Pendiente';

                tr.innerHTML = `
                    <td>
                        ${intercambio.titulo}
                        <br>
                        <small>
                            ${intercambio.autor
                                ? intercambio.autor
                                : 'Autor no indicado'}
                        </small>
                    </td>

                    <td>${intercambio.solicitante}</td>

                    <td>${intercambio.propietario}</td>

                    <td>${intercambio.estado}</td>

                    <td>${intercambio.fecha_inicio}</td>

                    <td>${fechaFinalizacion}</td>

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
            'No se lograron cargar los intercambios.',
            'error'
        );

    } finally {
        loader.classList.add('hidden');
        table.classList.remove('hidden');
    }
}


function completarIntercambio(id) {
    Swal.fire({
        title: '¿Completar intercambio?',
        text: 'El material dejará de estar disponible.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, completar',
        cancelButtonText: 'Cancelar'

    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(
                    `${BASE_URL}/intercambio/apiCompletar/${id}`,
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

                    loadIntercambios();

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
                    'Ocurrió un error al completar el intercambio.',
                    'error'
                );
            }
        }
    });
}