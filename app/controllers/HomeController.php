<?php
class HomeController extends Controller {

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
        $data = [
            'titulo' => 'Inicio - BookCycle',
            'usuario_nombre' => $_SESSION['user_nombre'] ?? 'Estudiante'
        ];

        $this->view('home/index', $data);
    }
}