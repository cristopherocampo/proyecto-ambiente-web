<?php
require_once __DIR__ . "/../config/Database.php";

class Admin
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function esAdministrador(int $usuario_id): bool
    {
        $sql = "SELECT usuario_id
                FROM usuario_roles
                WHERE usuario_id = ?
                AND rol_id = 2
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();

        return $stmt->get_result()->num_rows === 1;
    }

    public function getResumen(): array
    {
        return [
            "usuarios" => $this->scalar(
                "SELECT COUNT(*) FROM usuarios"
            ),
            "publicaciones" => $this->scalar(
                "SELECT COUNT(*) FROM publicaciones"
            ),
            "solicitudes" => $this->scalar(
                "SELECT COUNT(*) FROM solicitudes"
            ),
            "intercambios" => $this->scalar(
                "SELECT COUNT(*) FROM intercambios"
            ),
        ];
    }

    public function getUsuarios(): array
    {
        $sql = "SELECT
                    u.id,
                    u.nombre,
                    u.apellidos,
                    u.correo,
                    eu.nombre AS estado
                FROM usuarios u
                INNER JOIN estados_usuario eu
                    ON eu.id = u.estado_usuario_id
                ORDER BY u.nombre, u.apellidos";

        return $this->rows($sql);
    }

    public function getPublicaciones(): array
    {
        $sql = "SELECT
                    p.id,
                    o.titulo,
                    CONCAT(
                        u.nombre,
                        ' ',
                        u.apellidos
                    ) AS propietario,
                    ep.nombre AS estado,
                    m.nombre AS modalidad
                FROM publicaciones p
                INNER JOIN obras o
                    ON o.id = p.obra_id
                INNER JOIN usuarios u
                    ON u.id = p.propietario_id
                INNER JOIN estados_publicacion ep
                    ON ep.id = p.estado_publicacion_id
                INNER JOIN modalidades m
                    ON m.id = p.modalidad_id
                ORDER BY p.fecha_publicacion DESC";

        return $this->rows($sql);
    }

    public function getReportes(): array
    {
        $sql = "SELECT
                    r.id,
                    CONCAT(
                        u.nombre,
                        ' ',
                        u.apellidos
                    ) AS reportante,
                    r.motivo,
                    r.detalle,
                    er.nombre AS estado,
                    r.fecha_reporte
                FROM reportes r
                INNER JOIN usuarios u
                    ON u.id = r.reportante_id
                INNER JOIN estados_reporte er
                    ON er.id = r.estado_reporte_id
                ORDER BY r.fecha_reporte DESC";

        return $this->rows($sql);
    }

    public function desactivarPublicacion(int $id): bool
    {
        $sql = "UPDATE publicaciones
                SET estado_publicacion_id = 5
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function resolverReporte(
        int $id,
        int $administrador_id
    ): bool {
        $sql = "UPDATE reportes
                SET estado_reporte_id = 3,
                    administrador_id = ?,
                    fecha_resolucion = NOW()
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "ii",
            $administrador_id,
            $id
        );

        return $stmt->execute();
    }

    private function rows(string $sql): array
    {
        return $this->db
            ->query($sql)
            ->fetch_all(MYSQLI_ASSOC);
    }

    private function scalar(string $sql): int
    {
        return (int) $this->db
            ->query($sql)
            ->fetch_row()[0];
    }
}