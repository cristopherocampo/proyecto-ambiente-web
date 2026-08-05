<?php
require_once __DIR__ . '/../config/Database.php';

class Solicitud {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPublicacionById($id) {
        $query = "SELECT
                    p.*,
                    o.titulo,
                    o.descripcion,
                    CASE
                        WHEN p.estado_publicacion_id = 2 THEN 1
                        ELSE 0
                    END AS disponible
                  FROM publicaciones AS p
                  INNER JOIN obras AS o
                    ON p.obra_id = o.id
                  WHERE p.id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getById($id) {
        $query = "SELECT
                    solicitudes.*,
                    solicitudes.publicacion_solicitada_id AS publicacion_id,
                    o.titulo,
                    (
                        SELECT GROUP_CONCAT(
                            autores.nombre
                            ORDER BY obra_autores.orden_autoria
                            SEPARATOR ', '
                        )
                        FROM obra_autores
                        INNER JOIN autores
                            ON obra_autores.autor_id = autores.id
                        WHERE obra_autores.obra_id = o.id
                    ) AS autor,
                    publicaciones.propietario_id,
                    publicaciones.estado_publicacion_id,
                    publicaciones.modalidad_id,
                    publicaciones.valor_creditos,
                    CASE
                        WHEN publicaciones.estado_publicacion_id = 2 THEN 1
                        ELSE 0
                    END AS disponible,
                    estados_solicitud.nombre AS estado,
                    usuarios.nombre AS solicitante_nombre,
                    usuarios.apellidos AS solicitante_apellidos
                  FROM solicitudes
                  INNER JOIN publicaciones
                    ON solicitudes.publicacion_solicitada_id = publicaciones.id
                  INNER JOIN obras AS o
                    ON publicaciones.obra_id = o.id
                  INNER JOIN estados_solicitud
                    ON solicitudes.estado_solicitud_id = estados_solicitud.id
                  INNER JOIN usuarios
                    ON solicitudes.solicitante_id = usuarios.id
                  WHERE solicitudes.id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getEnviadas($usuario_id) {
        $query = "SELECT
                    solicitudes.*,
                    solicitudes.publicacion_solicitada_id AS publicacion_id,
                    o.titulo,
                    (
                        SELECT GROUP_CONCAT(
                            autores.nombre
                            ORDER BY obra_autores.orden_autoria
                            SEPARATOR ', '
                        )
                        FROM obra_autores
                        INNER JOIN autores
                            ON obra_autores.autor_id = autores.id
                        WHERE obra_autores.obra_id = o.id
                    ) AS autor,
                    estados_solicitud.nombre AS estado,
                    usuarios.nombre AS propietario_nombre,
                    usuarios.apellidos AS propietario_apellidos
                  FROM solicitudes
                  INNER JOIN publicaciones
                    ON solicitudes.publicacion_solicitada_id = publicaciones.id
                  INNER JOIN obras AS o
                    ON publicaciones.obra_id = o.id
                  INNER JOIN estados_solicitud
                    ON solicitudes.estado_solicitud_id = estados_solicitud.id
                  INNER JOIN usuarios
                    ON publicaciones.propietario_id = usuarios.id
                  WHERE solicitudes.solicitante_id = ?
                  ORDER BY solicitudes.id DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $solicitudes = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $solicitudes[] = $row;
            }
        }

        return $solicitudes;
    }

    public function getRecibidas($usuario_id) {
        $query = "SELECT
                    solicitudes.*,
                    solicitudes.publicacion_solicitada_id AS publicacion_id,
                    o.titulo,
                    (
                        SELECT GROUP_CONCAT(
                            autores.nombre
                            ORDER BY obra_autores.orden_autoria
                            SEPARATOR ', '
                        )
                        FROM obra_autores
                        INNER JOIN autores
                            ON obra_autores.autor_id = autores.id
                        WHERE obra_autores.obra_id = o.id
                    ) AS autor,
                    estados_solicitud.nombre AS estado,
                    usuarios.nombre AS solicitante_nombre,
                    usuarios.apellidos AS solicitante_apellidos
                  FROM solicitudes
                  INNER JOIN publicaciones
                    ON solicitudes.publicacion_solicitada_id = publicaciones.id
                  INNER JOIN obras AS o
                    ON publicaciones.obra_id = o.id
                  INNER JOIN estados_solicitud
                    ON solicitudes.estado_solicitud_id = estados_solicitud.id
                  INNER JOIN usuarios
                    ON solicitudes.solicitante_id = usuarios.id
                  WHERE publicaciones.propietario_id = ?
                  ORDER BY solicitudes.id DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $solicitudes = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $solicitudes[] = $row;
            }
        }

        return $solicitudes;
    }

    public function existePendiente($publicacion_id, $solicitante_id) {
        $query = "SELECT id
                  FROM solicitudes
                  WHERE publicacion_solicitada_id = ?
                  AND solicitante_id = ?
                  AND estado_solicitud_id = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "ii",
            $publicacion_id,
            $solicitante_id
        );

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function create($data) {
        $query = "INSERT INTO solicitudes
                  (
                      publicacion_solicitada_id,
                      solicitante_id,
                      modalidad_id,
                      estado_solicitud_id,
                      creditos_ofrecidos,
                      mensaje
                  )
                  SELECT
                      publicaciones.id,
                      ?,
                      publicaciones.modalidad_id,
                      1,
                      CASE
                          WHEN publicaciones.modalidad_id IN (2, 3)
                              THEN COALESCE(publicaciones.valor_creditos, 0)
                          ELSE NULL
                      END,
                      ?
                  FROM publicaciones
                  WHERE publicaciones.id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "isi",
            $data['solicitante_id'],
            $data['mensaje'],
            $data['publicacion_id']
        );

        if ($stmt->execute() && $stmt->affected_rows === 1) {
            return $this->db->insert_id;
        }

        return false;
    }

    public function updateEstado($id, $estado_id, $motivo_rechazo = null) {
        $query = "UPDATE solicitudes
                  SET estado_solicitud_id = ?,
                      motivo_rechazo = ?,
                      fecha_respuesta = NOW()
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "isi",
            $estado_id,
            $motivo_rechazo,
            $id
        );

        return $stmt->execute();
    }

    public function createHistorial($data) {
        $cambiado_por_id =
            $data['cambiado_por_id']
            ?? $data['cambiado_por']
            ?? null;

        $query = "INSERT INTO historial_estados_solicitud
                  (
                      solicitud_id,
                      estado_solicitud_id,
                      cambiado_por_id,
                      comentario
                  )
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "iiis",
            $data['solicitud_id'],
            $data['estado_solicitud_id'],
            $cambiado_por_id,
            $data['comentario']
        );

        return $stmt->execute();
    }
}