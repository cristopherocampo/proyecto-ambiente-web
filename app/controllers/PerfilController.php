<?php
class PerfilController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    public function index(): void
    {
        $m = $this->model("UserEstudiante");
        $this->view("perfil/index", [
            "usuario" => $m->getById((int) $_SESSION["user_id"]),
            "instituciones" => $m->getInstituciones(),
            "carreras" => $m->getCarreras(),
            "flash" => $_SESSION["flash"] ?? null,
            "csrf" => $this->csrfToken(),
        ]);
        unset($_SESSION["flash"]);
    }
    public function actualizar(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/perfil/index");
        }
        $this->verifyCsrf();
        $n = trim($_POST["nombre"] ?? "");
        $a = trim($_POST["apellidos"] ?? "");
        $i = (int) ($_POST["institucion_id"] ?? 0);
        $c = (int) ($_POST["carrera_id"] ?? 0);
        $m = $this->model("UserEstudiante");
        if (!$n || !$a || !$i || !$c) {
            $this->flash("error", "Completa todos los campos.");
        } elseif ($m->existeNombreCompleto($n, $a, (int) $_SESSION["user_id"])) {
            $this->flash("error", "Ese nombre ya pertenece a otro usuario.");
        } else {
            $m->updatePerfil((int) $_SESSION["user_id"], $n, $a, $i, $c);
            $_SESSION["user_nombre"] = $n;
            $this->flash("success", "Perfil actualizado correctamente.");
        }
        $this->redirect("/perfil/index");
    }
}
