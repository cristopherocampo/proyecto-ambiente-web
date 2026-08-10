<?php
require_once __DIR__ . "/../config/Database.php";

class Credito
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getSaldo(int $usuario_id): float
    {
        $sql = "SELECT saldo
                FROM vw_saldos_credito
                WHERE usuario_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return (float) ($result["saldo"] ?? 0);
    }

    public function tieneSaldo(
        int $usuario_id,
        float $monto
    ): bool {
        return $this->getSaldo($usuario_id) >= $monto;
    }

    public function registrarMovimiento(
        int $usuario_id,
        ?int $intercambio_id,
        int $tipo_movimiento_id,
        float $monto,
        string $descripcion
    ): bool {
        $sql = "INSERT INTO movimientos_credito (
                    usuario_id,
                    intercambio_id,
                    tipo_movimiento_id,
                    monto,
                    descripcion
                ) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "iiids",
            $usuario_id,
            $intercambio_id,
            $tipo_movimiento_id,
            $monto,
            $descripcion
        );

        return $stmt->execute();
    }
}
