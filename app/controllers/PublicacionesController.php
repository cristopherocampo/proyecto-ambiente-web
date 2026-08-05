<?php
require_once __DIR__ . "/../core/PublicationInput.php";
class PublicacionesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    public function index(): void
    {
        $this->mis_publicaciones();
    }
    public function mis_publicaciones(): void
    {
        $m = $this->model("Publicacion");
        $this->view("publicaciones/mis", [
            "publicaciones" => $m->list([], true, (int) $_SESSION["user_id"]),
            "csrf" => $this->csrfToken(),
            "flash" => $_SESSION["flash"] ?? null,
        ]);
        unset($_SESSION["flash"]);
    }
    public function crear(): void
    {
        $m = $this->model("Publicacion");
        $this->view("publicaciones/form", [
            "catalogos" => $m->catalogs(),
            "csrf" => $this->csrfToken(),
            "publicacion" => null,
        ]);
    }
    public function guardar(): void
    {
        $this->save(null);
    }
    public function editar($id = 0): void
    {
        $m = $this->model("Publicacion");
        $p = $m->find((int) $id);
        if (!$p) {
            http_response_code(404);
            exit("Publicación no encontrada.");
        }
        if ((int) $p["propietario_id"] !== (int) $_SESSION["user_id"]) {
            http_response_code(403);
            exit("No puedes editar esta publicación.");
        }
        $this->view("publicaciones/form", [
            "catalogos" => $m->catalogs(),
            "csrf" => $this->csrfToken(),
            "publicacion" => $p,
        ]);
    }
    public function actualizar($id = 0): void
    {
        $this->save((int) $id);
    }
    private function save(?int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/catalogo/index");
        }
        $this->verifyCsrf();
        $m = $this->model("Publicacion");
        [$d, $errors] = PublicationInput::validate($_POST, $m);
        [$image, $uploadError] = PublicationInput::upload($_FILES["imagen"] ?? []);
        if ($uploadError) {
            $errors[] = $uploadError;
        }
        if (!$id && !$image) {
            $errors[] = "La imagen es obligatoria.";
        }
        if ($errors) {
            if ($image) {
                @unlink(dirname(__DIR__, 2) . "/" . $image);
            }
            $this->view("publicaciones/form", [
                "catalogos" => $m->catalogs(),
                "csrf" => $this->csrfToken(),
                "publicacion" => array_merge($_POST, ["id" => $id]),
                "errors" => $errors,
            ]);
            return;
        }
        try {
            $id
                ? $m->update($id, (int) $_SESSION["user_id"], $d, $image)
                : $m->create($d, (int) $_SESSION["user_id"], $image);
            $this->flash("success", $id ? "Publicación actualizada." : "Publicación creada.");
            $this->redirect("/publicaciones/mis-publicaciones");
        } catch (Throwable $e) {
            if ($image) {
                @unlink(dirname(__DIR__, 2) . "/" . $image);
            }
            error_log($e);
            $this->view("publicaciones/form", [
                "catalogos" => $m->catalogs(),
                "csrf" => $this->csrfToken(),
                "publicacion" => array_merge($_POST, ["id" => $id]),
                "errors" => ["No se pudo guardar la publicación."],
            ]);
        }
    }
    public function detalle($id = 0): void
    {
        $p = $this->model("Publicacion")->find((int) $id);
        if (!$p || $p["estado_publicacion"] === "INACTIVA") {
            http_response_code(404);
            exit("Publicación no encontrada.");
        }
        $this->view("publicaciones/detalle", ["publicacion" => $p, "csrf" => $this->csrfToken()]);
    }
    public function disponibilidad($id = 0): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/publicaciones/mis-publicaciones");
        }
        $this->verifyCsrf();
        $status = (int) ($_POST["estado_publicacion_id"] ?? 0);
        $m = $this->model("Publicacion");
        if (
            !$m->validCatalog("estados_publicacion", $status) ||
            !$m->setStatus((int) $id, (int) $_SESSION["user_id"], $status)
        ) {
            $this->flash("error", "No se pudo cambiar la disponibilidad.");
        } else {
            $this->flash("success", "Disponibilidad actualizada.");
        }
        $this->redirect("/publicaciones/mis-publicaciones");
    }
    public function eliminar($id = 0): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/publicaciones/mis-publicaciones");
        }
        $this->verifyCsrf();
        $ok = $this->model("Publicacion")->deactivate((int) $id, (int) $_SESSION["user_id"]);
        $this->flash(
            $ok ? "success" : "error",
            $ok ? "Publicación eliminada." : "No puedes eliminar esa publicación.",
        );
        $this->redirect("/publicaciones/mis-publicaciones");
    }
    public function solicitar($id = 0): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/catalogo/index");
        }
        $this->verifyCsrf();
        $p = $this->model("Publicacion")->find((int) $id);
        if (!$p) {
            http_response_code(404);
            exit("Publicación no encontrada.");
        }
        if ((int) $p["propietario_id"] === (int) $_SESSION["user_id"]) {
            http_response_code(422);
            exit("No puedes solicitar tu propio material.");
        }
        $this->flash("success", "La publicación puede ser solicitada por este usuario.");
        $this->redirect("/publicaciones/detalle/" . (int) $id);
    }
}
