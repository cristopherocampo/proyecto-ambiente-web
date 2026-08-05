<?php
class PublicationInput
{
    public static function validate(array $input, Publicacion $model): array
    {
        $d = [
            "titulo" => trim($input["titulo"] ?? ""),
            "autor" => trim($input["autor"] ?? ""),
            "descripcion" => trim($input["descripcion"] ?? ""),
            "edicion" => trim($input["edicion"] ?? ""),
            "observaciones" => trim($input["observaciones"] ?? ""),
            "tipo_material_id" => (int) ($input["tipo_material_id"] ?? 0),
            "categoria_id" => (int) ($input["categoria_id"] ?? 0),
            "curso_id" => (int) ($input["curso_id"] ?? 0),
            "estado_fisico_id" => (int) ($input["estado_fisico_id"] ?? 0),
            "estado_publicacion_id" => (int) ($input["estado_publicacion_id"] ?? 0),
            "modalidad_id" => (int) ($input["modalidad_id"] ?? 0),
            "valor_creditos" => (float) ($input["valor_creditos"] ?? 0),
        ];
        $errors = [];
        foreach (["titulo" => "Título", "autor" => "Autor", "descripcion" => "Descripción"] as $k => $label) {
            if ($d[$k] === "") {
                $errors[] = "$label es obligatorio.";
            }
        }
        if (mb_strlen($d["titulo"]) > 220) {
            $errors[] = "El título supera 220 caracteres.";
        }
        if (mb_strlen($d["autor"]) > 180) {
            $errors[] = "El autor supera 180 caracteres.";
        }
        foreach (
            [
                "tipo_material_id" => "tipos_material",
                "categoria_id" => "categorias",
                "curso_id" => "cursos",
                "estado_fisico_id" => "estados_fisicos",
                "estado_publicacion_id" => "estados_publicacion",
                "modalidad_id" => "modalidades",
            ]
            as $k => $table
        ) {
            if (!$d[$k] || !$model->validCatalog($table, $d[$k])) {
                $errors[] = "Valor inválido para $k.";
            }
        }
        if ($d["valor_creditos"] < 0) {
            $errors[] = "Los créditos no pueden ser negativos.";
        }
        return [$d, $errors];
    }
    public static function upload(array $file): array
    {
        if (($file["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return [null, null];
        }
        if ($file["error"] !== UPLOAD_ERR_OK) {
            return [null, "No se pudo cargar la imagen."];
        }
        if ($file["size"] > MAX_UPLOAD_SIZE) {
            return [null, "La imagen supera 5 MB."];
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file["tmp_name"]);
        $map = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp"];
        if (!isset($map[$mime])) {
            return [null, "Solo se permiten imágenes JPG, PNG o WebP."];
        }
        if (!is_dir(UPLOAD_DIR) && !mkdir(UPLOAD_DIR, 0775, true)) {
            return [null, "No se pudo preparar la carpeta de imágenes."];
        }
        $name = bin2hex(random_bytes(16)) . "." . $map[$mime];
        if (!move_uploaded_file($file["tmp_name"], UPLOAD_DIR . "/" . $name)) {
            return [null, "No se pudo guardar la imagen."];
        }
        return ["public/uploads/" . $name, null];
    }
}
