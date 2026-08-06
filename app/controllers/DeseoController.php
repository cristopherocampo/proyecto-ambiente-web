<?php

class DeseoController extends Controller
{
    private $deseoModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();

        $this->deseoModel = $this->model("Deseo");
    }

    public function index(): void
    {
        $usuarioId = (int) $_SESSION["user_id"];

        $this->view("deseos/index", [
            "deseos" => $this->deseoModel->getByUsuario(
                $usuarioId
            ),
            "coincidencias" =>
                $this->deseoModel->getCoincidencias(
                    $usuarioId
                ),
            "csrf" => $this->csrfToken(),
            "flash" => $_SESSION["flash"] ?? null,
        ]);

        unset($_SESSION["flash"]);
    }

    public function agregar($publicacionId = 0): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/catalogo/index");
        }

        $this->verifyCsrf();

        $publicacionId = (int) $publicacionId;
        $usuarioId = (int) $_SESSION["user_id"];

        if ($publicacionId <= 0) {
            $this->flash(
                "error",
                "La publicación seleccionada no es válida."
            );

            $this->redirect("/catalogo/index");
        }

        try {
            $resultado = $this->deseoModel->agregar(
                $usuarioId,
                $publicacionId
            );

            if ($resultado) {
                $this->flash(
                    "success",
                    "Material agregado a tu lista de deseos."
                );
            } else {
                $this->flash(
                    "error",
                    "No se pudo agregar el material a tu lista."
                );
            }
        } catch (Throwable $e) {
            error_log($e);

            $this->flash(
                "error",
                "Ocurrió un error al agregar el material."
            );
        }

        $this->redirect("/deseo/index");
    }

    public function eliminar($id = 0): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/deseo/index");
        }

        $this->verifyCsrf();

        $id = (int) $id;
        $usuarioId = (int) $_SESSION["user_id"];

        if ($id <= 0) {
            $this->flash(
                "error",
                "El material seleccionado no es válido."
            );

            $this->redirect("/deseo/index");
        }

        try {
            $resultado = $this->deseoModel->eliminar(
                $id,
                $usuarioId
            );

            if ($resultado) {
                $this->flash(
                    "success",
                    "Material eliminado de tu lista de deseos."
                );
            } else {
                $this->flash(
                    "error",
                    "No se pudo eliminar el material."
                );
            }
        } catch (Throwable $e) {
            error_log($e);

            $this->flash(
                "error",
                "Ocurrió un error al eliminar el material."
            );
        }

        $this->redirect("/deseo/index");
    }
}