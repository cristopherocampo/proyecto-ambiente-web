<?php
class App
{
    protected $controller = "AuthController";
    protected string $method = "index";
    protected array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();
        if (isset($url[0])) {
            $candidate = ucfirst(str_replace("-", "", $url[0])) . "Controller";
            if (file_exists(__DIR__ . "/../controllers/" . $candidate . ".php")) {
                $this->controller = $candidate;
                unset($url[0]);
            }
        }
        require_once __DIR__ . "/../controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller();
        if (isset($url[1])) {
            $candidate = str_replace("-", "_", $url[1]);
            if (method_exists($this->controller, $candidate)) {
                $this->method = $candidate;
                unset($url[1]);
            }
        }
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl(): array
    {
        $raw = $_GET["url"] ?? "";
        $raw = trim((string) $raw, "/");
        return $raw === "" ? [] : explode("/", filter_var($raw, FILTER_SANITIZE_URL));
    }
}
