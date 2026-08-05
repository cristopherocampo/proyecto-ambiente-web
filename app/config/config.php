<?php
define("DB_HOST", getenv("BOOKCYCLE_DB_HOST") ?: "127.0.0.1");
define("DB_USER", getenv("BOOKCYCLE_DB_USER") ?: "root");
define("DB_PASS", getenv("BOOKCYCLE_DB_PASS") ?: "");
define("DB_NAME", getenv("BOOKCYCLE_DB_NAME") ?: "bookcycle");

$https = !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off";
$protocol = $https ? "https://" : "http://";
$domainName = $_SERVER["HTTP_HOST"] ?? "localhost";
$script = str_replace("\\", "/", $_SERVER["SCRIPT_NAME"] ?? "/index.php");
$path = rtrim(dirname($script), "/");
define("BASE_URL", $protocol . $domainName . ($path === "/" ? "" : $path));
define("UPLOAD_DIR", dirname(__DIR__, 2) . "/public/uploads");
define("MAX_UPLOAD_SIZE", 5 * 1024 * 1024);
