<?php
class RegistroController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(): void
    {
        if (!empty($_SESSION["user_id"])) {
            $this->redirect("/catalogo/index");
        }
        $m = $this->model("UserEstudiante");
        $this->view("auth/registro", [
            "instituciones" => $m->getInstituciones(),
            "carreras" => $m->getCarreras(),
            "csrf" => $this->csrfToken(),
        ]);
    }
    public function registrar(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/registro/index");
        }
        $this->verifyCsrf();
        $m = $this->model("UserEstudiante");
        $data = [
            "instituciones" => $m->getInstituciones(),
            "carreras" => $m->getCarreras(),
            "csrf" => $this->csrfToken(),
        ];
        $nombre = trim($_POST["nombre"] ?? "");
        $apellidos = trim($_POST["apellidos"] ?? "");
        $correo = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
        $password = $_POST["password"] ?? "";
        $inst = (int) ($_POST["institucion_id"] ?? 0);
        $carrera = (int) ($_POST["carrera_id"] ?? 0);
        if ($nombre === "" || $apellidos === "" || !$correo || strlen($password) < 8 || !$inst || !$carrera) {
            $data["error"] = "Completa todos los campos; la contraseña debe tener al menos 8 caracteres.";
        } elseif ($m->getByEmail($correo)) {
            $data["error"] = "El correo ya está registrado.";
        } elseif ($m->existeNombreCompleto($nombre, $apellidos)) {
            $data["error"] = "Ya existe una cuenta con ese nombre y apellido.";
        } else {
            try {
                $m->create([
                    "nombre" => $nombre,
                    "apellidos" => $apellidos,
                    "correo" => $correo,
                    "password_hash" => password_hash($password, PASSWORD_DEFAULT),
                    "institucion_id" => $inst,
                    "carrera_id" => $carrera,
                ]);
                $this->redirect("/auth/index?exito=1");
            } catch (Throwable $e) {
                error_log($e);
                $data["error"] = "No fue posible completar el registro.";
            }
        }
        $this->view("auth/registro", $data);
    }
}
