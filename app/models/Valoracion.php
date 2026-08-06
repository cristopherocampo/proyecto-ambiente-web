<?php
require_once __DIR__ . '/../config/Database.php';

class Valoracion {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getIntercambioParaValorar(
        $intercambio_id,
        $usuario_id
    ) {
        $query = "SELECT
                    intercambios.id,
                    intercambios.estado_intercambio_id,
                    estados_intercambio.nombre AS estado,
                    solicitudes.solicitante_id,
                    publicaciones.propietario_id,
                    obras.titulo,
                    CONCAT(
                        usuario_solicitante.nombre,
                        ' ',
                        usuario_solicitante.apellidos
                    ) AS solicitante,
                    CONCAT(
                        usuario_propietario.nombre,
                        ' ',
                        usuario_propietario.apellidos
                    ) AS propietario,
                    CASE
                        WHEN solicitudes.solicitante_id = ?
                            THEN publicaciones.propietario_id
                        ELSE solicitudes.solicitante_id
                    END AS evaluado_id,
                    CASE
                        WHEN solicitudes.solicitante_id = ?
                            THEN CONCAT(
                                usuario_propietario.nombre,
                                ' ',
                                usuario_propietario.apellidos
                            )
                        ELSE CONCAT(
                            usuario_solicitante.nombre,
                            ' ',
                            usuario_solicitante.apellidos
                        )
                    END AS evaluado
                  FROM intercambios
                  INNER JOIN estados_intercambio
                    ON intercambios.estado_intercambio_id =
                       estados_intercambio.id
                  INNER JOIN solicitudes
                    ON intercambios.solicitud_id =
                       solicitudes.id
                  INNER JOIN publicaciones
                    ON solicitudes.publicacion_solicitada_id =
                       publicaciones.id
                  INNER JOIN obras
                    ON publicaciones.obra_id = obras.id
                  INNER JOIN usuarios AS usuario_solicitante
                    ON solicitudes.solicitante_id =
                       usuario_solicitante.id
                  INNER JOIN usuarios AS usuario_propietario
                    ON publicaciones.propietario_id =
                       usuario_propietario.id
                  WHERE intercambios.id = ?
                  AND (
                        solicitudes.solicitante_id = ?
                        OR publicaciones.propietario_id = ?
                  )";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "iiiii",
            $usuario_id,
            $usuario_id,
            $intercambio_id,
            $usuario_id,
            $usuario_id
        );

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function existeValoracion(
        $intercambio_id,
        $autor_id
    ) {
        $query = "SELECT id
                  FROM valoraciones
                  WHERE intercambio_id = ?
                  AND autor_id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "ii",
            $intercambio_id,
            $autor_id
        );

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function create($data) {
        $query = "INSERT INTO valoraciones
                  (
                      intercambio_id,
                      autor_id,
                      evaluado_id,
                      puntuacion,
                      comentario
                  )
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "iiiis",
            $data['intercambio_id'],
            $data['autor_id'],
            $data['evaluado_id'],
            $data['puntuacion'],
            $data['comentario']
        );

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }

        return false;
    }

    public function getByIntercambio($intercambio_id) {
        $query = "SELECT
                    valoraciones.*,
                    CONCAT(
                        autor.nombre,
                        ' ',
                        autor.apellidos
                    ) AS autor,
                    CONCAT(
                        evaluado.nombre,
                        ' ',
                        evaluado.apellidos
                    ) AS evaluado
                  FROM valoraciones
                  INNER JOIN usuarios AS autor
                    ON valoraciones.autor_id = autor.id
                  INNER JOIN usuarios AS evaluado
                    ON valoraciones.evaluado_id = evaluado.id
                  WHERE valoraciones.intercambio_id = ?
                  ORDER BY valoraciones.fecha_valoracion DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $intercambio_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $valoraciones = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $valoraciones[] = $row;
            }
        }

        return $valoraciones;
    }
}