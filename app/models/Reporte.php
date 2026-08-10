<?php
require_once __DIR__ . "/../config/Database.php";

class Reporte
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(
        int $reportante_id,
        int $publicacion_id,
        string $motivo,
        string $detalle
    ): bool {
        $sql = "INSERT INTO reportes (
                    reportante_id,
                    publicacion_reportada_id,
                    estado_reporte_id,
                    motivo,
                    detalle
                ) VALUES (?, ?, 1, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "iiss",
            $reportante_id,
            $publicacion_id,
            $motivo,
            $detalle
        );

        return $stmt->execute();
    }
}