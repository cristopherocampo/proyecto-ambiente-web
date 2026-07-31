<?php
class PerfilController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
            exit;
        }
    }

    public function index() {
        $userModel = $this->model('UserEstudiante');
        
        $usuario = $userModel->getById($_SESSION['user_id']);
        $instituciones = $userModel->getInstituciones();
        $carreras = $userModel->getCarreras();

        $data = [
            'titulo'        => 'Mi Perfil - BookCycle',
            'usuario'       => $usuario,
            'instituciones' => $instituciones,
            'carreras'      => $carreras,
            'mensaje'       => $_SESSION['perfil_mensaje'] ?? null
        ];

        unset($_SESSION['perfil_mensaje']);

        $this->view('perfil/index', $data);
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre         = trim($_POST['nombre'] ?? '');
            $apellidos      = trim($_POST['apellidos'] ?? '');
            $institucion_id = $_POST['institucion_id'] ?? '';
            $carrera_id     = $_POST['carrera_id'] ?? '';

            if (!empty($nombre) && !empty($apellidos)) {
                $userModel = $this->model('UserEstudiante');
                
                $actualizado = $userModel->updatePerfil(
                    $_SESSION['user_id'], 
                    $nombre, 
                    $apellidos, 
                    $institucion_id, 
                    $carrera_id
                );

                if ($actualizado) {
                    $_SESSION['user_nombre'] = $nombre;
                    $_SESSION['perfil_mensaje'] = ['tipo' => 'exito', 'texto' => '¡Perfil e información académica actualizados con éxito!'];
                } else {
                    $_SESSION['perfil_mensaje'] = ['tipo' => 'error', 'texto' => 'Error al actualizar los datos.'];
                }
            } else {
                $_SESSION['perfil_mensaje'] = ['tipo' => 'error', 'texto' => 'Por favor, completa los campos requeridos.'];
            }
        }

        $this->redirect('/perfil/index');
    }
}