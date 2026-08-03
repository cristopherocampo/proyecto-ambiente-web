<?php
require_once __DIR__ . '/../config/Database.php';

class Solicitud {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPublicacionById($id) {
        $query = "SELECT * FROM publicaciones WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getById($id) {
        $query = "SELECT 
                    solicitudes.*,
                    publicaciones.titulo,
                    publicaciones.autor,
                    publicaciones.propietario_id,
                    publicaciones.disponible,
                    estados_solicitud.nombre AS estado,
                    usuarios.nombre AS solicitante_nombre,
                    usuarios.apellidos AS solicitante_apellidos
                  FROM solicitudes
                  INNER JOIN publicaciones
                    ON solicitudes.publicacion_id = publicaciones.id
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
                    publicaciones.titulo,
                    publicaciones.autor,
                    estados_solicitud.nombre AS estado,
                    usuarios.nombre AS propietario_nombre,
                    usuarios.apellidos AS propietario_apellidos
                  FROM solicitudes
                  INNER JOIN publicaciones
                    ON solicitudes.publicacion_id = publicaciones.id
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
                    publicaciones.titulo,
                    publicaciones.autor,
                    estados_solicitud.nombre AS estado,
                    usuarios.nombre AS solicitante_nombre,
                    usuarios.apellidos AS solicitante_apellidos
                  FROM solicitudes
                  INNER JOIN publicaciones
                    ON solicitudes.publicacion_id = publicaciones.id
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
        $query = "SELECT * FROM solicitudes
                  WHERE publicacion_id = ?
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
                  (publicacion_id, solicitante_id, estado_solicitud_id, mensaje)
                  VALUES (?, ?, 1, ?)";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "iis",
            $data['publicacion_id'],
            $data['solicitante_id'],
            $data['mensaje']
        );

        if ($stmt->execute()) {
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
        $query = "INSERT INTO historial_estados_solicitud
                  (solicitud_id, estado_solicitud_id, cambiado_por, comentario)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "iiis",
            $data['solicitud_id'],
            $data['estado_solicitud_id'],
            $data['cambiado_por'],
            $data['comentario']
        );

        return $stmt->execute();
    }
}