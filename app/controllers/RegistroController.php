<?php
require_once 'app/core/Controller.php';

class RegistroController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/user/index');
        }

        $userModel = $this->model('UserEstudiante');
        
        $data = [
            'instituciones' => $userModel->getInstituciones(),
            'carreras'      => $userModel->getCarreras()
        ];

        $this->view('auth/registro', $data);
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre         = trim($_POST['nombre'] ?? '');
            $apellidos      = trim($_POST['apellidos'] ?? '');
            $email          = trim($_POST['email'] ?? '');
            $password       = $_POST['password'] ?? '';
            $institucion_id = $_POST['institucion_id'] ?? null;
            $carrera_id     = $_POST['carrera_id'] ?? null;

            $userModel = $this->model('UserEstudiante');

            
            $data = [
                'instituciones' => $userModel->getInstituciones(),
                'carreras'      => $userModel->getCarreras()
            ];

            // Validar que los campos no estan vacios
            if (!empty($nombre) && !empty($apellidos) && !empty($email) && !empty($password)) {
                
                // Verificar si el correo ya está registrado
                if ($userModel->getByEmail($email)) {
                    $data['error'] = 'El correo ya se encuentra registrado.';
                    $this->view('auth/registro', $data);
                    return;
                }

               
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                $registrado = $userModel->create([
                    'nombre'         => $nombre,
                    'apellidos'      => $apellidos,
                    'correo'         => $email,
                    'password'       => $passwordHash,
                    'institucion_id' => $institucion_id,
                    'carrera_id'     => $carrera_id
                ]);

                if ($registrado) {
                    $this->redirect('/auth/index?exito=1');
                } else {
                    $data['error'] = 'Ocurrió un error al registrar el usuario.';
                    $this->view('auth/registro', $data);
                }
            } else {
                $data['error'] = 'Por favor completa todos los campos requeridos.';
                $this->view('auth/registro', $data);
            }
        } else {
            $this->redirect('/registro/index');
        }
    }
}