<?php

class ValoracionController extends Controller {
    private $valoracionModel;

    public function __construct() {
        parent::__construct();

        $this->requireAuth();

        $this->valoracionModel =
            $this->model('Valoracion');
    }

    public function crear($intercambio_id = 0) {
        $intercambio_id = (int)$intercambio_id;
        $usuario_id = (int)$_SESSION['user_id'];

        $intercambio =
            $this->valoracionModel
                ->getIntercambioParaValorar(
                    $intercambio_id,
                    $usuario_id
                );

        if (!$intercambio) {
            $this->flash(
                'error',
                'No tienes permiso para valorar este intercambio.'
            );

            $this->redirect('/intercambio/index');
        }

        if (
            (int)$intercambio['estado_intercambio_id'] !== 4
        ) {
            $this->flash(
                'error',
                'El intercambio debe estar finalizado antes de valorarlo.'
            );

            $this->redirect('/intercambio/index');
        }

        $existeValoracion =
            $this->valoracionModel
                ->existeValoracion(
                    $intercambio_id,
                    $usuario_id
                );

        if ($existeValoracion) {
            $this->flash(
                'error',
                'Ya realizaste una valoración para este intercambio.'
            );

            $this->redirect('/intercambio/index');
        }

        $this->view('valoraciones/crear', [
            'intercambio' => $intercambio,
            'csrf' => $this->csrfToken()
        ]);
    }

    public function guardar($intercambio_id = 0) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/intercambio/index');
        }

        $this->verifyCsrf();

        $intercambio_id = (int)$intercambio_id;
        $usuario_id = (int)$_SESSION['user_id'];

        $puntuacion = (int)(
            $_POST['puntuacion'] ?? 0
        );

        $comentario = trim(
            $_POST['comentario'] ?? ''
        );

        $intercambio =
            $this->valoracionModel
                ->getIntercambioParaValorar(
                    $intercambio_id,
                    $usuario_id
                );

        if (!$intercambio) {
            $this->flash(
                'error',
                'No tienes permiso para valorar este intercambio.'
            );

            $this->redirect('/intercambio/index');
        }

        if (
            (int)$intercambio['estado_intercambio_id'] !== 4
        ) {
            $this->flash(
                'error',
                'El intercambio debe estar finalizado antes de valorarlo.'
            );

            $this->redirect('/intercambio/index');
        }

        if ($puntuacion < 1 || $puntuacion > 5) {
            $this->view('valoraciones/crear', [
                'intercambio' => $intercambio,
                'csrf' => $this->csrfToken(),
                'errors' => [
                    'La puntuación debe estar entre 1 y 5.'
                ],
                'comentario' => $comentario
            ]);

            return;
        }

        if (mb_strlen($comentario) > 600) {
            $this->view('valoraciones/crear', [
                'intercambio' => $intercambio,
                'csrf' => $this->csrfToken(),
                'errors' => [
                    'El comentario no puede superar los 600 caracteres.'
                ],
                'puntuacion' => $puntuacion,
                'comentario' => $comentario
            ]);

            return;
        }

        $existeValoracion =
            $this->valoracionModel
                ->existeValoracion(
                    $intercambio_id,
                    $usuario_id
                );

        if ($existeValoracion) {
            $this->flash(
                'error',
                'Ya realizaste una valoración para este intercambio.'
            );

            $this->redirect('/intercambio/index');
        }

        try {
            $valoracion_id =
                $this->valoracionModel->create([
                    'intercambio_id' => $intercambio_id,
                    'autor_id' => $usuario_id,
                    'evaluado_id' =>
                        (int)$intercambio['evaluado_id'],
                    'puntuacion' => $puntuacion,
                    'comentario' => $comentario
                ]);

            if (!$valoracion_id) {
                $this->flash(
                    'error',
                    'No se pudo guardar la valoración.'
                );

                $this->redirect('/intercambio/index');
            }

            $this->flash(
                'success',
                'Valoración guardada correctamente.'
            );

            $this->redirect('/intercambio/index');
        } catch (Throwable $e) {
            error_log($e);

            $this->flash(
                'error',
                'Ocurrió un error al guardar la valoración.'
            );

            $this->redirect('/intercambio/index');
        }
    }
}