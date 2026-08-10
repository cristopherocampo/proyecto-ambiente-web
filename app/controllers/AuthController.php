<?php
class AuthController extends Controller
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
        $this->view("auth/login", [
            "exito" => isset($_GET["exito"]) ? "¡Registro exitoso! Ya puedes iniciar sesión." : null,
            "csrf" => $this->csrfToken(),
        ]);
    }
    public function login(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/auth/index");
        }
        $this->verifyCsrf();
        $email = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
        $user = $email ? $this->model("UserEstudiante")->getByEmail($email) : null;
        if (
            $user &&
            (int) $user["estado_usuario_id"] === 2 &&
            password_verify($_POST["password"] ?? "", $user["password_hash"])
        ) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = (int) $user["id"];
            $_SESSION["user_nombre"] = $user["nombre"];
            $_SESSION["es_admin"] = $this->model("Admin")->esAdministrador(
                (int) $user["id"]
            );
            $this->redirect("/catalogo/index");
        }
        $this->view("auth/login", [
            "error" => "Correo o contraseña incorrectos.",
            "csrf" => $this->csrfToken(),
        ]);
    }
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect("/auth/index");
    }
}
