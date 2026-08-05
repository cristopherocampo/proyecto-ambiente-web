<?php
require_once __DIR__ . '/../config/Database.php';

class Intercambio {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getBySolicitud($solicitud_id) {
        $query = "SELECT *
                  FROM intercambios
                  WHERE solicitud_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $solicitud_id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function create($solicitud_id) {
        $codigo_entrega = strtoupper(
            substr(
                md5(uniqid('', true)),
                0,
                10
            )
        );

        $query = "INSERT INTO intercambios
                  (
                      solicitud_id,
                      estado_intercambio_id,
                      codigo_entrega,
                      fecha_acordada
                  )
                  VALUES (?, 1, ?, NOW())";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "is",
            $solicitud_id,
            $codigo_entrega
        );

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }

        return false;
    }

    public function getByUsuario($usuario_id) {
        $query = "SELECT
                    intercambios.*,
                    intercambios.fecha_acordada AS fecha_inicio,
                    estados_intercambio.nombre AS estado,
                    solicitudes.publicacion_solicitada_id AS publicacion_id,
                    solicitudes.solicitante_id,
                    publicaciones.propietario_id,
                    obras.titulo,
                    (
                        SELECT GROUP_CONCAT(
                            autores.nombre
                            ORDER BY obra_autores.orden_autoria
                            SEPARATOR ', '
                        )
                        FROM obra_autores
                        INNER JOIN autores
                            ON obra_autores.autor_id = autores.id
                        WHERE obra_autores.obra_id = obras.id
                    ) AS autor,
                    CONCAT(
                        usuario_solicitante.nombre,
                        ' ',
                        usuario_solicitante.apellidos
                    ) AS solicitante,
                    CONCAT(
                        usuario_propietario.nombre,
                        ' ',
                        usuario_propietario.apellidos
                    ) AS propietario
                  FROM intercambios
                  INNER JOIN estados_intercambio
                    ON intercambios.estado_intercambio_id =
                       estados_intercambio.id
                  INNER JOIN solicitudes
                    ON intercambios.solicitud_id = solicitudes.id
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
                  WHERE solicitudes.solicitante_id = ?
                     OR publicaciones.propietario_id = ?
                  ORDER BY intercambios.id DESC";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "ii",
            $usuario_id,
            $usuario_id
        );

        $stmt->execute();

        $result = $stmt->get_result();
        $intercambios = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $intercambios[] = $row;
            }
        }

        return $intercambios;
    }

    public function getById($id) {
        $query = "SELECT
                    intercambios.*,
                    intercambios.fecha_acordada AS fecha_inicio,
                    solicitudes.publicacion_solicitada_id AS publicacion_id,
                    solicitudes.solicitante_id,
                    publicaciones.propietario_id,
                    obras.titulo
                  FROM intercambios
                  INNER JOIN solicitudes
                    ON intercambios.solicitud_id = solicitudes.id
                  INNER JOIN publicaciones
                    ON solicitudes.publicacion_solicitada_id =
                       publicaciones.id
                  INNER JOIN obras
                    ON publicaciones.obra_id = obras.id
                  WHERE intercambios.id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function completar($id) {
        $query = "UPDATE intercambios
                  SET estado_intercambio_id = 4,
                      fecha_finalizacion = NOW()
                  WHERE id = ?
                  AND estado_intercambio_id = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function completarSolicitud($solicitud_id) {
        $query = "UPDATE solicitudes
                  SET estado_solicitud_id = 6,
                      fecha_respuesta = NOW()
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $solicitud_id);

        return $stmt->execute();
    }

    public function finalizarPublicacion($publicacion_id) {
        $query = "UPDATE publicaciones
                  SET estado_publicacion_id = 4
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $publicacion_id);

        return $stmt->execute();
    }
}