<?php
require_once __DIR__ . "/../config/Database.php";

class UserEstudiante
{
    private mysqli $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }
    public function getById(int $id): ?array
    {
        $sql = "SELECT u.*, i.nombre AS institucion, c.nombre AS carrera
                FROM usuarios u
                JOIN instituciones i ON i.id = u.institucion_id
                LEFT JOIN carreras c ON c.id = u.carrera_id
                WHERE u.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }
    public function create(array $d): bool
    {
        $estado = $this->scalar("SELECT id FROM estados_usuario WHERE nombre='ACTIVO' LIMIT 1");
        $sql = "INSERT INTO usuarios (
                    institucion_id, carrera_id, estado_usuario_id,
                    nombre, apellidos, correo, password_hash
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "iiissss",
            $d["institucion_id"],
            $d["carrera_id"],
            $estado,
            $d["nombre"],
            $d["apellidos"],
            $d["correo"],
            $d["password_hash"],
        );
        return $stmt->execute();
    }
    public function updatePerfil(
        int $id,
        string $nombre,
        string $apellidos,
        int $institucion,
        int $carrera,
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE usuarios SET nombre=?,apellidos=?,institucion_id=?,carrera_id=? WHERE id=?",
        );
        $stmt->bind_param("ssiii", $nombre, $apellidos, $institucion, $carrera, $id);
        return $stmt->execute();
    }
    public function existeNombreCompleto(string $nombre, string $apellidos, ?int $exclude = null): bool
    {
        $sql =
            "SELECT id FROM usuarios WHERE LOWER(nombre)=LOWER(?) AND LOWER(apellidos)=LOWER(?)" .
            ($exclude ? " AND id<>?" : "");
        $stmt = $this->db->prepare($sql);
        $exclude
            ? $stmt->bind_param("ssi", $nombre, $apellidos, $exclude)
            : $stmt->bind_param("ss", $nombre, $apellidos);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
    public function getInstituciones(): array
    {
        return $this->rows("SELECT id,nombre FROM instituciones ORDER BY nombre");
    }
    public function getCarreras(): array
    {
        return $this->rows("SELECT id,institucion_id,nombre FROM carreras WHERE activo=1 ORDER BY nombre");
    }
    private function rows(string $sql): array
    {
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    private function scalar(string $sql): int
    {
        return (int) $this->db->query($sql)->fetch_row()[0];
    }
}
