<?php

class ReporteController extends Controller
{
    private $reporteModel;

    public function __construct()
    {
        parent::__construct();

        $this->requireAuth();

        $this->reporteModel = $this->model("Reporte");
    }

    public function guardar($id = 0): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/catalogo/index");
        }

        $this->verifyCsrf();

        $publicacion_id = (int) $id;
        $motivo = trim($_POST["motivo"] ?? "");
        $detalle = trim($_POST["detalle"] ?? "");

        if ($motivo === "") {
            $this->flash(
                "error",
                "Debes indicar el motivo del reporte."
            );

            $this->redirect(
                "/publicaciones/detalle/" .
                $publicacion_id
            );
        }

        if (strlen($motivo) > 120) {
            $this->flash(
                "error",
                "El motivo es demasiado largo."
            );

            $this->redirect(
                "/publicaciones/detalle/" .
                $publicacion_id
            );
        }

        if (strlen($detalle) > 1000) {
            $this->flash(
                "error",
                "El detalle es demasiado largo."
            );

            $this->redirect(
                "/publicaciones/detalle/" .
                $publicacion_id
            );
        }

        $ok = $this->reporteModel->create(
            (int) $_SESSION["user_id"],
            $publicacion_id,
            $motivo,
            $detalle
        );

        $this->flash(
            $ok ? "success" : "error",
            $ok
                ? "Reporte enviado correctamente."
                : "No se pudo enviar el reporte."
        );

        $this->redirect(
            "/publicaciones/detalle/" .
            $publicacion_id
        );
    }
}