<?php
require_once __DIR__ . '/../config/Database.php';

class UserEstudiante {
    private $db;

    public function __construct() {
        
        $this->db = Database::getInstance()->getConnection();
    }

    // Buscar usuario por correo
    public function getByEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Registrar nuevo usuario
    public function create($data) {
        $sql = "INSERT INTO usuarios (nombre, apellidos, correo, password, institucion_id, carrera_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        
        $instId = !empty($data['institucion_id']) ? (int)$data['institucion_id'] : null;
        $carreraId = !empty($data['carrera_id']) ? (int)$data['carrera_id'] : null;

        $stmt->bind_param(
            "ssssii", 
            $data['nombre'], 
            $data['apellidos'], 
            $data['correo'], 
            $data['password'], 
            $instId, 
            $carreraId
        );

        return $stmt->execute();
    }

    
    public function getInstituciones() {
        $sql = "SELECT * FROM instituciones ORDER BY nombre ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    
    public function getCarreras() {
        $sql = "SELECT * FROM carreras ORDER BY nombre ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    
    public function getById($id) {
        $sql = "SELECT u.*, i.nombre as institucion, c.nombre as carrera 
                FROM usuarios u
                LEFT JOIN instituciones i ON u.institucion_id = i.id
                LEFT JOIN carreras c ON u.carrera_id = c.id
                WHERE u.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    
    public function updatePerfil($id, $nombre, $apellidos, $institucion_id, $carrera_id) {
    $sql = "UPDATE usuarios 
            SET nombre = ?, apellidos = ?, institucion_id = ?, carrera_id = ? 
            WHERE id = ?";
            
    $stmt = $this->db->prepare($sql);
    
    $instId = !empty($institucion_id) ? (int)$institucion_id : null;
    $carreraId = !empty($carrera_id) ? (int)$carrera_id : null;

    $stmt->bind_param("ssiii", $nombre, $apellidos, $instId, $carreraId, $id);
    return $stmt->execute();
    }
}