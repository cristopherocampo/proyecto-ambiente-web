<?php
class PerfilController extends Controller {

    public function __construct() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si el usuario no ha iniciado sesión, lo mandamos al login
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
            exit;
        }
    }

    public function index() {
        $userModel = $this->model('UserEstudiante');
        
        // Consultar los datos actualizados del estudiante logueado
        $usuario = $userModel->getById($_SESSION['user_id']);

        $data = [
            'titulo' => 'Mi Perfil - BookCycle',
            'usuario' => $usuario,
            'mensaje' => $_SESSION['perfil_mensaje'] ?? null
        ];

        // Limpiar mensaje temporal tras mostrarlo
        unset($_SESSION['perfil_mensaje']);

        $this->view('perfil/index', $data);
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');

            if (!empty($nombre) && !empty($apellidos)) {
                $userModel = $this->model('UserEstudiante');
                $actualizado = $userModel->updatePerfil($_SESSION['user_id'], $nombre, $apellidos);

                if ($actualizado) {
                    $_SESSION['user_nombre'] = $nombre; // Actualizamos la sesión activa
                    $_SESSION['perfil_mensaje'] = ['tipo' => 'exito', 'texto' => '¡Perfil actualizado con éxito!'];
                } else {
                    $_SESSION['perfil_mensaje'] = ['tipo' => 'error', 'texto' => 'Error al actualizar los datos.'];
                }
            } else {
                $_SESSION['perfil_mensaje'] = ['tipo' => 'error', 'texto' => 'Por favor, completa todos los campos.'];
            }
        }

        $this->redirect('/perfil/index');
    }
}