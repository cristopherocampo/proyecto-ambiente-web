<?php
class Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    protected function model(string $model)
    {
        require_once __DIR__ . "/../models/" . $model . ".php";
        return new $model();
    }
    protected function view(string $view, array $data = []): void
    {
        if (!empty($_SESSION["user_id"])) {
            $creditoModel = $this->model("Credito");

            $data["saldo_creditos"] = $creditoModel->getSaldo(
                (int) $_SESSION["user_id"]
            );
        }

        $file = __DIR__ . "/../views/" . $view . ".php";
        if (!file_exists($file)) {
            http_response_code(404);
            exit("Vista no encontrada.");
        }
        require $file;
    }
    protected function redirect(string $url): never
    {
        header("Location: " . BASE_URL . $url);
        exit();
    }
    protected function requireAuth(): void
    {
        if (empty($_SESSION["user_id"])) {
            $this->redirect("/auth/index");
        }
    }
    protected function csrfToken(): string
    {
        if (empty($_SESSION["csrf"])) {
            $_SESSION["csrf"] = bin2hex(random_bytes(32));
        }
        return $_SESSION["csrf"];
    }
    protected function verifyCsrf(): void
    {
        if (!hash_equals($_SESSION["csrf"] ?? "", $_POST["csrf"] ?? ($_SERVER["HTTP_X_CSRF_TOKEN"] ?? ""))) {
            http_response_code(419);
            exit("Solicitud inválida o vencida.");
        }
    }
    protected function flash(string $type, string $message): void
    {
        $_SESSION["flash"] = compact("type", "message");
    }
}
