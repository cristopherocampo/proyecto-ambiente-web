<?php
require_once 'app/core/Controller.php';

class AuthController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
    $this->redirect('/home/index');
}
        
        $data = [];
        if (isset($_GET['exito'])) {
            $data['exito'] = '¡Registro exitoso! Ya puedes iniciar sesión.';
        }

        $this->view('auth/login', $data);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('UserEstudiante');
            $user = $userModel->getByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                
                $this->redirect('/home/index');
            } else {
                $this->view('auth/login', ['error' => 'Correo o contraseña incorrectos']);
            }
        } else {
            $this->redirect('/auth/index');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/auth/index');
    }
}