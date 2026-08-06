<?php

require_once __DIR__ . "/../config/Database.php";

class Deseo
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByUsuario(int $usuarioId): array
    {
        $sql = "SELECT
                    d.id,
                    d.obra_id,
                    d.fecha_registro,
                    o.titulo,
                    o.descripcion,
                    o.edicion,
                    tm.nombre AS tipo_material,
                    GROUP_CONCAT(
                        DISTINCT a.nombre
                        ORDER BY oa.orden_autoria
                        SEPARATOR ', '
                    ) AS autor,
                    GROUP_CONCAT(
                        DISTINCT cat.nombre
                        ORDER BY cat.nombre
                        SEPARATOR ', '
                    ) AS categoria,
                    GROUP_CONCAT(
                        DISTINCT cur.nombre
                        ORDER BY cur.nombre
                        SEPARATOR ', '
                    ) AS curso
                FROM deseos AS d
                INNER JOIN obras AS o
                    ON d.obra_id = o.id
                INNER JOIN tipos_material AS tm
                    ON o.tipo_material_id = tm.id
                LEFT JOIN obra_autores AS oa
                    ON o.id = oa.obra_id
                LEFT JOIN autores AS a
                    ON oa.autor_id = a.id
                LEFT JOIN obra_categorias AS oc
                    ON o.id = oc.obra_id
                LEFT JOIN categorias AS cat
                    ON oc.categoria_id = cat.id
                LEFT JOIN obra_cursos AS ocu
                    ON o.id = ocu.obra_id
                LEFT JOIN cursos AS cur
                    ON ocu.curso_id = cur.id
                WHERE d.usuario_id = ?
                  AND d.activo = 1
                GROUP BY
                    d.id,
                    d.obra_id,
                    d.fecha_registro,
                    o.titulo,
                    o.descripcion,
                    o.edicion,
                    tm.nombre
                ORDER BY d.fecha_registro DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function agregar(
        int $usuarioId,
        int $publicacionId
    ): bool {
        $sql = "SELECT
                    p.obra_id,
                    p.propietario_id
                FROM publicaciones AS p
                INNER JOIN estados_publicacion AS ep
                    ON p.estado_publicacion_id = ep.id
                WHERE p.id = ?
                  AND ep.nombre <> 'INACTIVA'";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $publicacionId);
        $stmt->execute();

        $publicacion = $stmt
            ->get_result()
            ->fetch_assoc();

        if (!$publicacion) {
            return false;
        }

        if (
            (int) $publicacion["propietario_id"] ===
            $usuarioId
        ) {
            return false;
        }

        $obraId = (int) $publicacion["obra_id"];

        $sql = "SELECT id
                FROM deseos
                WHERE usuario_id = ?
                  AND obra_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ii",
            $usuarioId,
            $obraId
        );

        $stmt->execute();

        $deseo = $stmt
            ->get_result()
            ->fetch_assoc();

        if ($deseo) {
            $sql = "UPDATE deseos
                    SET activo = 1,
                        fecha_registro = NOW()
                    WHERE id = ?
                      AND usuario_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                "ii",
                $deseo["id"],
                $usuarioId
            );

            return $stmt->execute();
        }

        $sql = "INSERT INTO deseos
                (
                    usuario_id,
                    obra_id,
                    activo
                )
                VALUES (?, ?, 1)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ii",
            $usuarioId,
            $obraId
        );

        return $stmt->execute();
    }

    public function eliminar(
        int $id,
        int $usuarioId
    ): bool {
        $sql = "UPDATE deseos
                SET activo = 0
                WHERE id = ?
                  AND usuario_id = ?
                  AND activo = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ii",
            $id,
            $usuarioId
        );

        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function getCoincidencias(
        int $usuarioId
    ): array {
        $sql = "SELECT
                    p.id AS publicacion_id,
                    p.obra_id,
                    p.valor_creditos,
                    p.fecha_publicacion,
                    o.titulo,
                    o.descripcion,
                    ef.nombre AS estado_fisico,
                    ep.nombre AS estado_publicacion,
                    m.nombre AS modalidad,
                    CONCAT(
                        u.nombre,
                        ' ',
                        u.apellidos
                    ) AS propietario,
                    GROUP_CONCAT(
                        DISTINCT a.nombre
                        ORDER BY oa.orden_autoria
                        SEPARATOR ', '
                    ) AS autor,
                    MAX(
                        CASE
                            WHEN pf.es_portada = 1
                            THEN pf.url
                            ELSE NULL
                        END
                    ) AS imagen
                FROM deseos AS d
                INNER JOIN obras AS obra_deseada
                    ON d.obra_id = obra_deseada.id
                INNER JOIN obras AS o
                    ON LOWER(TRIM(o.titulo)) =
                       LOWER(TRIM(obra_deseada.titulo))
                INNER JOIN publicaciones AS p
                    ON p.obra_id = o.id
                INNER JOIN estados_fisicos AS ef
                    ON p.estado_fisico_id = ef.id
                INNER JOIN estados_publicacion AS ep
                    ON p.estado_publicacion_id = ep.id
                INNER JOIN modalidades AS m
                    ON p.modalidad_id = m.id
                INNER JOIN usuarios AS u
                    ON p.propietario_id = u.id
                LEFT JOIN obra_autores AS oa
                    ON o.id = oa.obra_id
                LEFT JOIN autores AS a
                    ON oa.autor_id = a.id
                LEFT JOIN publicacion_fotos AS pf
                    ON p.id = pf.publicacion_id
                WHERE d.usuario_id = ?
                  AND d.activo = 1
                  AND ep.nombre = 'DISPONIBLE'
                  AND p.propietario_id <> ?
                GROUP BY
                    p.id,
                    p.obra_id,
                    p.valor_creditos,
                    p.fecha_publicacion,
                    o.titulo,
                    o.descripcion,
                    ef.nombre,
                    ep.nombre,
                    m.nombre,
                    u.nombre,
                    u.apellidos
                ORDER BY p.fecha_publicacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ii",
            $usuarioId,
            $usuarioId
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }
}