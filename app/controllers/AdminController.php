<?php

class AdminController extends Controller
{
    private $adminModel;

    public function __construct()
    {
        parent::__construct();

        $this->requireAuth();

        $this->adminModel = $this->model("Admin");

        if (
            !$this->adminModel->esAdministrador(
                (int) $_SESSION["user_id"]
            )
        ) {
            http_response_code(403);
            exit("No tienes permiso para acceder a esta sección.");
        }
    }

    public function index(): void
    {
        $this->view("admin/index", [
            "resumen" => $this->adminModel->getResumen(),
            "usuarios" => $this->adminModel->getUsuarios(),
            "publicaciones" => $this->adminModel->getPublicaciones(),
            "reportes" => $this->adminModel->getReportes(),
            "csrf" => $this->csrfToken(),
            "flash" => $_SESSION["flash"] ?? null,
        ]);

        unset($_SESSION["flash"]);
    }

    public function desactivar($id = 0): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/admin/index");
        }

        $this->verifyCsrf();

        $ok = $this->adminModel->desactivarPublicacion(
            (int) $id
        );

        $this->flash(
            $ok ? "success" : "error",
            $ok
                ? "Publicación desactivada."
                : "No se pudo desactivar la publicación."
        );

        $this->redirect("/admin/index");
    }

    public function resolver($id = 0): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/admin/index");
        }

        $this->verifyCsrf();

        $ok = $this->adminModel->resolverReporte(
            (int) $id,
            (int) $_SESSION["user_id"]
        );

        $this->flash(
            $ok ? "success" : "error",
            $ok
                ? "Reporte resuelto."
                : "No se pudo resolver el reporte."
        );

        $this->redirect("/admin/index");
    }
}