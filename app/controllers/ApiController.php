<?php
require_once __DIR__ . "/../core/PublicationInput.php";
class ApiController extends Controller
{
    private Publicacion $publicacion;
    public function __construct()
    {
        parent::__construct();
        header("Content-Type: application/json; charset=utf-8");
        $this->publicacion = $this->model("Publicacion");
    }
    public function index(): void
    {
        $this->json(["publicaciones" => "/api/publicaciones", "catalogos" => "/api/catalogos"]);
    }
    public function catalogos(): void
    {
        if (!$this->auth()) {
            return;
        }
        $this->json(array_merge($this->publicacion->catalogs(), ["csrf" => $this->csrfToken()]));
    }
    public function publicaciones($id = null, $action = null): void
    {
        if (!$this->auth()) {
            return;
        }
        $method = $_SERVER["REQUEST_METHOD"];
        if ($id === null && $method === "GET") {
            $f = array_intersect_key(
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
            $this->json($this->publicacion->list($f));
            return;
        }
        if ($id !== null && !ctype_digit((string) $id)) {
            $this->fail(422, "El ID no es válido.");
            return;
        }
        $id = (int) $id;
        if ($id && $method === "GET") {
            $p = $this->publicacion->find($id);
            $p ? $this->json($p) : $this->fail(404, "Publicación no encontrada.");
            return;
        }
        if (!in_array($method, ["POST", "PUT", "PATCH", "DELETE"], true)) {
            $this->fail(405, "Método no permitido.");
            return;
        }
        $this->apiCsrf();
        $input = $_POST;
        if (in_array($method, ["PUT", "PATCH"], true)) {
            $raw = json_decode(file_get_contents("php://input"), true);
            if (is_array($raw)) {
                $input = $raw;
            }
        }
        if ($id === 0 && $method === "POST") {
            $this->create($input);
            return;
        }
        $existing = $this->publicacion->find($id);
        if (!$existing) {
            $this->fail(404, "Publicación no encontrada.");
            return;
        }
        if ((int) $existing["propietario_id"] !== (int) $_SESSION["user_id"]) {
            $this->fail(403, "No puedes modificar una publicación ajena.");
            return;
        }
        if ($method === "DELETE") {
            $this->publicacion->deactivate($id, (int) $_SESSION["user_id"]);
            $this->json(["id" => $id, "estado" => "INACTIVA"]);
            return;
        }
        if ($method === "PATCH" && $action === "disponibilidad") {
            $status = (int) ($input["estado_publicacion_id"] ?? 0);
            if (!$this->publicacion->validCatalog("estados_publicacion", $status)) {
                $this->fail(422, "Estado inválido.");
                return;
            }
            $this->publicacion->setStatus($id, (int) $_SESSION["user_id"], $status);
            $this->json(["id" => $id, "estado_publicacion_id" => $status]);
            return;
        }
        if ($method === "PUT") {
            [$d, $errors] = PublicationInput::validate($input, $this->publicacion);
            if ($errors) {
                $this->fail(422, "Datos inválidos.", $errors);
                return;
            }
            $this->publicacion->update($id, (int) $_SESSION["user_id"], $d, null);
            $this->json($this->publicacion->find($id));
            return;
        }
        $this->fail(405, "Operación no permitida.");
    }
    private function create(array $input): void
    {
        [$d, $errors] = PublicationInput::validate($input, $this->publicacion);
        [$image, $uploadError] = PublicationInput::upload($_FILES["imagen"] ?? []);
        if ($uploadError) {
            $errors[] = $uploadError;
        }
        if (!$image) {
            $errors[] = "La imagen es obligatoria.";
        }
        if ($errors) {
            if ($image) {
                @unlink(dirname(__DIR__, 2) . "/" . $image);
            }
            $this->fail(422, "Datos inválidos.", $errors);
            return;
        }
        try {
            $id = $this->publicacion->create($d, (int) $_SESSION["user_id"], $image);
            http_response_code(201);
            $this->json($this->publicacion->find($id));
        } catch (Throwable $e) {
            if ($image) {
                @unlink(dirname(__DIR__, 2) . "/" . $image);
            }
            error_log($e);
            $this->fail(500, "No se pudo crear la publicación.");
        }
    }
    private function auth(): bool
    {
        if (empty($_SESSION["user_id"])) {
            $this->fail(401, "Debes iniciar sesión.");
            return false;
        }
        return true;
    }
    private function apiCsrf(): void
    {
        if (!hash_equals($_SESSION["csrf"] ?? "", $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "")) {
            $this->fail(419, "Token CSRF inválido.");
            exit();
        }
    }
    private function json($data): void
    {
        echo json_encode(
            ["success" => true, "data" => $data, "error" => null],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        );
    }
    private function fail(int $status, string $message, array $details = []): void
    {
        http_response_code($status);
        echo json_encode(
            ["success" => false, "data" => null, "error" => ["message" => $message, "details" => $details]],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        );
    }
}
