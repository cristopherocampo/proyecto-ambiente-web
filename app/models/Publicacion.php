<?php
require_once __DIR__ . "/../config/Database.php";

class Publicacion
{
    private mysqli $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    private function baseSelect(): string
    {
        return <<<'SQL'
            SELECT
                p.id,
                p.obra_id,
                p.propietario_id,
                p.estado_fisico_id,
                p.estado_publicacion_id,
                p.modalidad_id,
                p.valor_creditos,
                p.observaciones,
                p.fecha_publicacion,
                o.titulo,
                o.descripcion,
                o.edicion,
                o.tipo_material_id,
                tm.nombre AS tipo_material,
                ef.nombre AS estado_fisico,
                ep.nombre AS estado_publicacion,
                m.nombre AS modalidad,
                CONCAT(u.nombre, ' ', u.apellidos) AS propietario,
                GROUP_CONCAT(
                    DISTINCT a.nombre
                    ORDER BY oa.orden_autoria
                    SEPARATOR ', '
                ) AS autor,
                MAX(cat.id) AS categoria_id,
                GROUP_CONCAT(DISTINCT cat.nombre ORDER BY cat.nombre SEPARATOR ', ') AS categoria,
                MAX(cur.id) AS curso_id,
                GROUP_CONCAT(DISTINCT cur.nombre ORDER BY cur.nombre SEPARATOR ', ') AS curso,
                MAX(car.id) AS carrera_id,
                GROUP_CONCAT(DISTINCT car.nombre ORDER BY car.nombre SEPARATOR ', ') AS carrera,
                MAX(CASE WHEN pf.es_portada = 1 THEN pf.url ELSE NULL END) AS imagen
            FROM publicaciones p
            JOIN obras o ON o.id = p.obra_id
            JOIN tipos_material tm ON tm.id = o.tipo_material_id
            JOIN estados_fisicos ef ON ef.id = p.estado_fisico_id
            JOIN estados_publicacion ep ON ep.id = p.estado_publicacion_id
            JOIN modalidades m ON m.id = p.modalidad_id
            JOIN usuarios u ON u.id = p.propietario_id
            LEFT JOIN obra_autores oa ON oa.obra_id = o.id
            LEFT JOIN autores a ON a.id = oa.autor_id
            LEFT JOIN obra_categorias oc ON oc.obra_id = o.id
            LEFT JOIN categorias cat ON cat.id = oc.categoria_id
            LEFT JOIN obra_cursos ocu ON ocu.obra_id = o.id
            LEFT JOIN cursos cur ON cur.id = ocu.curso_id
            LEFT JOIN carreras car ON car.id = cur.carrera_id
            LEFT JOIN publicacion_fotos pf ON pf.publicacion_id = p.id
            SQL;
    }
    private function groupBy(): string
    {
        return <<<'SQL'
            GROUP BY
                p.id,
                p.obra_id,
                p.propietario_id,
                p.estado_fisico_id,
                p.estado_publicacion_id,
                p.modalidad_id,
                p.valor_creditos,
                p.observaciones,
                p.fecha_publicacion,
                o.titulo,
                o.descripcion,
                o.edicion,
                o.tipo_material_id,
                tm.nombre,
                ef.nombre,
                ep.nombre,
                m.nombre,
                u.nombre,
                u.apellidos
            SQL;
    }

    public function list(array $f = [], bool $own = false, ?int $userId = null): array
    {
        $where = [];
        $types = "";
        $args = [];
        $where[] = "ep.nombre<>'INACTIVA'";
        if ($own) {
            $where[] = "p.propietario_id=?";
            $types .= "i";
            $args[] = $userId;
        }
        if (!empty($f["q"])) {
            $where[] = "(o.titulo LIKE ? OR a.nombre LIKE ?)";
            $v = "%" . $f["q"] . "%";
            $types .= "ss";
            $args[] = $v;
            $args[] = $v;
        }
        if (!empty($f["autor"])) {
            $where[] = "a.nombre LIKE ?";
            $types .= "s";
            $args[] = "%" . $f["autor"] . "%";
        }
        foreach (
            [
                "categoria_id" => "cat.id",
                "carrera_id" => "car.id",
                "curso_id" => "cur.id",
                "estado_publicacion_id" => "p.estado_publicacion_id",
            ]
            as $k => $col
        ) {
            if (!empty($f[$k])) {
                $where[] = "$col=?";
                $types .= "i";
                $args[] = (int) $f[$k];
            }
        }
        $page = max(1, (int) ($f["page"] ?? 1));
        $limit = min(50, max(1, (int) ($f["limit"] ?? 12)));
        $offset = ($page - 1) * $limit;
        $sql =
            $this->baseSelect() .
            ($where ? " WHERE " . implode(" AND ", $where) : "") .
            $this->groupBy() .
            " ORDER BY p.fecha_publicacion DESC LIMIT ? OFFSET ?";
        $types .= "ii";
        $args[] = $limit;
        $args[] = $offset;
        return $this->preparedRows($sql, $types, $args);
    }
    public function find(int $id): ?array
    {
        $rows = $this->preparedRows($this->baseSelect() . " WHERE p.id=?" . $this->groupBy(), "i", [$id]);
        return $rows[0] ?? null;
    }
    public function catalogs(): array
    {
        return [
            "tipos" => $this->rows("SELECT id,nombre FROM tipos_material ORDER BY nombre"),
            "categorias" => $this->rows("SELECT id,nombre FROM categorias WHERE activo=1 ORDER BY nombre"),
            "carreras" => $this->rows(
                "SELECT id,institucion_id,nombre FROM carreras WHERE activo=1 ORDER BY nombre",
            ),
            "cursos" => $this->rows(
                "SELECT id,carrera_id,codigo,nombre FROM cursos WHERE activo=1 ORDER BY nombre",
            ),
            "estados_fisicos" => $this->rows("SELECT id,nombre FROM estados_fisicos ORDER BY id"),
            "estados_publicacion" => $this->rows(
                "SELECT id,nombre FROM estados_publicacion WHERE nombre<>'INACTIVA' ORDER BY id",
            ),
            "modalidades" => $this->rows("SELECT id,nombre FROM modalidades ORDER BY id"),
        ];
    }
    public function validCatalog(string $table, int $id): bool
    {
        $allowed = [
            "tipos_material",
            "categorias",
            "cursos",
            "estados_fisicos",
            "estados_publicacion",
            "modalidades",
        ];
        if (!in_array($table, $allowed, true)) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT id FROM $table WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->num_rows === 1;
    }

    public function create(array $d, int $owner, ?string $image): int
    {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO obras(tipo_material_id,titulo,edicion,descripcion) VALUES(?,?,?,?)",
            );
            $stmt->bind_param("isss", $d["tipo_material_id"], $d["titulo"], $d["edicion"], $d["descripcion"]);
            $stmt->execute();
            $obra = $this->db->insert_id;
            $author = $this->authorId($d["autor"]);
            $stmt = $this->db->prepare(
                "INSERT INTO obra_autores(obra_id,autor_id,orden_autoria) VALUES(?,?,1)",
            );
            $stmt->bind_param("ii", $obra, $author);
            $stmt->execute();
            $stmt = $this->db->prepare("INSERT INTO obra_categorias(obra_id,categoria_id) VALUES(?,?)");
            $stmt->bind_param("ii", $obra, $d["categoria_id"]);
            $stmt->execute();
            $stmt = $this->db->prepare("INSERT INTO obra_cursos(obra_id,curso_id) VALUES(?,?)");
            $stmt->bind_param("ii", $obra, $d["curso_id"]);
            $stmt->execute();
            $sql = "INSERT INTO publicaciones (
                        obra_id, propietario_id, estado_fisico_id,
                        estado_publicacion_id, modalidad_id,
                        valor_creditos, observaciones
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                "iiiiids",
                $obra,
                $owner,
                $d["estado_fisico_id"],
                $d["estado_publicacion_id"],
                $d["modalidad_id"],
                $d["valor_creditos"],
                $d["observaciones"],
            );
            $stmt->execute();
            $id = $this->db->insert_id;
            if ($image) {
                $stmt = $this->db->prepare(
                    "INSERT INTO publicacion_fotos(publicacion_id,url,orden,es_portada) VALUES(?,?,1,1)",
                );
                $stmt->bind_param("is", $id, $image);
                $stmt->execute();
            }
            $this->db->commit();
            return $id;
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    public function update(int $id, int $owner, array $d, ?string $image): bool
    {
        $p = $this->find($id);
        if (!$p || $p["propietario_id"] != $owner) {
            return false;
        }
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare(
                "UPDATE obras SET tipo_material_id=?,titulo=?,edicion=?,descripcion=? WHERE id=?",
            );
            $stmt->bind_param(
                "isssi",
                $d["tipo_material_id"],
                $d["titulo"],
                $d["edicion"],
                $d["descripcion"],
                $p["obra_id"],
            );
            $stmt->execute();
            $author = $this->authorId($d["autor"]);
            $stmt = $this->db->prepare("DELETE FROM obra_autores WHERE obra_id=?");
            $stmt->bind_param("i", $p["obra_id"]);
            $stmt->execute();
            $stmt = $this->db->prepare("INSERT INTO obra_autores VALUES(?,?,1)");
            $stmt->bind_param("ii", $p["obra_id"], $author);
            $stmt->execute();
            foreach (
                [
                    ["obra_categorias", "categoria_id", $d["categoria_id"]],
                    ["obra_cursos", "curso_id", $d["curso_id"]],
                ]
                as [$table, $col, $value]
            ) {
                $stmt = $this->db->prepare("DELETE FROM $table WHERE obra_id=?");
                $stmt->bind_param("i", $p["obra_id"]);
                $stmt->execute();
                $stmt = $this->db->prepare("INSERT INTO $table(obra_id,$col) VALUES(?,?)");
                $stmt->bind_param("ii", $p["obra_id"], $value);
                $stmt->execute();
            }
            $sql = "UPDATE publicaciones
                    SET estado_fisico_id = ?,
                        estado_publicacion_id = ?,
                        modalidad_id = ?,
                        valor_creditos = ?,
                        observaciones = ?
                    WHERE id = ? AND propietario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                "iiidsii",
                $d["estado_fisico_id"],
                $d["estado_publicacion_id"],
                $d["modalidad_id"],
                $d["valor_creditos"],
                $d["observaciones"],
                $id,
                $owner,
            );
            $stmt->execute();
            if ($image) {
                $stmt = $this->db->prepare("DELETE FROM publicacion_fotos WHERE publicacion_id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt = $this->db->prepare(
                    "INSERT INTO publicacion_fotos(publicacion_id,url,orden,es_portada) VALUES(?,?,1,1)",
                );
                $stmt->bind_param("is", $id, $image);
                $stmt->execute();
            }
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    public function setStatus(int $id, int $owner, int $status): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE publicaciones SET estado_publicacion_id=? WHERE id=? AND propietario_id=?",
        );
        $stmt->bind_param("iii", $status, $id, $owner);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
    public function deactivate(int $id, int $owner): bool
    {
        $status = (int) $this->db
            ->query("SELECT id FROM estados_publicacion WHERE nombre='INACTIVA'")
            ->fetch_row()[0];
        return $this->setStatus($id, $owner, $status);
    }
    private function authorId(string $name): int
    {
        $stmt = $this->db->prepare("SELECT id FROM autores WHERE LOWER(nombre)=LOWER(?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        if ($r) {
            return (int) $r["id"];
        }
        $stmt = $this->db->prepare("INSERT INTO autores(nombre) VALUES(?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        return $this->db->insert_id;
    }
    private function rows(string $sql): array
    {
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    private function preparedRows(string $sql, string $types, array $args): array
    {
        $stmt = $this->db->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$args);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
