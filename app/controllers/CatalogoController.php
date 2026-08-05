<?php
class CatalogoController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    public function index(): void
    {
        $m = $this->model("Publicacion");
        $filters = array_intersect_key(
            $_GET,
            array_flip([
                "q",
                "autor",
                "categoria_id",
                "carrera_id",
                "curso_id",
                "estado_publicacion_id",
                "page",
                "limit",
            ]),
        );
        $this->view("catalogo/index", [
            "publicaciones" => $m->list($filters),
            "catalogos" => $m->catalogs(),
            "filters" => $filters,
            "flash" => $_SESSION["flash"] ?? null,
        ]);
        unset($_SESSION["flash"]);
    }
}
