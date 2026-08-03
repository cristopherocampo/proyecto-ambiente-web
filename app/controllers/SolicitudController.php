<?php

class SolicitudController extends Controller {
    private $solicitudModel;
    private $intercambioModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
        }

        $this->solicitudModel = $this->model('Solicitud');
        $this->intercambioModel = $this->model('Intercambio');
    }
    public function index() {
        $this->view('solicitudes/index');
    }

    public function apiEnviadas() {
        header('Content-Type: application/json');

        $solicitudes = $this->solicitudModel->getEnviadas(
            $_SESSION['user_id']
        );

        echo json_encode($solicitudes);
    }

    public function apiRecibidas() {
        header('Content-Type: application/json');

        $solicitudes = $this->solicitudModel->getRecibidas(
            $_SESSION['user_id']
        );

        echo json_encode($solicitudes);
    }

    public function apiShow($id) {
        header('Content-Type: application/json');

        $solicitud = $this->solicitudModel->getById($id);

        if ($solicitud) {
            echo json_encode([
                'success' => true,
                'data' => $solicitud
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ]);
        }
    }

    public function apiStore() {
        header('Content-Type: application/json');

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $publicacion_id = $data['publicacion_id'] ?? '';
        $mensaje = trim($data['mensaje'] ?? '');

        if (empty($publicacion_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'La publicación es requerida'
            ]);
            return;
        }

        $publicacion = $this->solicitudModel->getPublicacionById(
            $publicacion_id
        );

        if (!$publicacion) {
            echo json_encode([
                'success' => false,
                'message' => 'La publicación no existe'
            ]);
            return;
        }

        if ((int)$publicacion['propietario_id'] === (int)$_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'No puedes solicitar tu propia publicación'
            ]);
            return;
        }

        if ((int)$publicacion['disponible'] !== 1) {
            echo json_encode([
                'success' => false,
                'message' => 'La publicación no está disponible'
            ]);
            return;
        }

        $existe = $this->solicitudModel->existePendiente(
            $publicacion_id,
            $_SESSION['user_id']
        );

        if ($existe) {
            echo json_encode([
                'success' => false,
                'message' => 'Ya existe una solicitud pendiente'
            ]);
            return;
        }

        $solicitud_id = $this->solicitudModel->create([
            'publicacion_id' => $publicacion_id,
            'solicitante_id' => $_SESSION['user_id'],
            'mensaje' => $mensaje
        ]);

        if ($solicitud_id) {
            $this->solicitudModel->createHistorial([
                'solicitud_id' => $solicitud_id,
                'estado_solicitud_id' => 1,
                'cambiado_por' => $_SESSION['user_id'],
                'comentario' => 'Solicitud creada'
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Solicitud enviada exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al enviar la solicitud'
            ]);
        }
    }

    public function apiAceptar($id) {
        header('Content-Type: application/json');

        $solicitud = $this->solicitudModel->getById($id);

        if (!$solicitud) {
            echo json_encode([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ]);
            return;
        }

        if ((int)$solicitud['propietario_id'] !== (int)$_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permiso para aceptar esta solicitud'
            ]);
            return;
        }

        if ((int)$solicitud['estado_solicitud_id'] !== 1) {
            echo json_encode([
                'success' => false,
                'message' => 'La solicitud ya fue procesada'
            ]);
            return;
        }

        $result = $this->solicitudModel->updateEstado(
            $id,
            2
        );

       if ($result) {
    $intercambio = $this->intercambioModel->getBySolicitud($id);

    if (!$intercambio) {
        $this->intercambioModel->create($id);
    }

    $this->solicitudModel->createHistorial([
        'solicitud_id' => $id,
        'estado_solicitud_id' => 2,
        'cambiado_por' => $_SESSION['user_id'],
        'comentario' => 'Solicitud aceptada'
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Solicitud aceptada exitosamente'
    ]);
}
    }

    public function apiRechazar($id) {
        header('Content-Type: application/json');

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $motivo = trim($data['motivo'] ?? '');

        if (empty($motivo)) {
            echo json_encode([
                'success' => false,
                'message' => 'Debes indicar el motivo del rechazo'
            ]);
            return;
        }

        $solicitud = $this->solicitudModel->getById($id);

        if (!$solicitud) {
            echo json_encode([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ]);
            return;
        }

        if ((int)$solicitud['propietario_id'] !== (int)$_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permiso para rechazar esta solicitud'
            ]);
            return;
        }

        if ((int)$solicitud['estado_solicitud_id'] !== 1) {
            echo json_encode([
                'success' => false,
                'message' => 'La solicitud ya fue procesada'
            ]);
            return;
        }

        $result = $this->solicitudModel->updateEstado(
            $id,
            3,
            $motivo
        );

        if ($result) {
            $this->solicitudModel->createHistorial([
                'solicitud_id' => $id,
                'estado_solicitud_id' => 3,
                'cambiado_por' => $_SESSION['user_id'],
                'comentario' => $motivo
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Solicitud rechazada'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al rechazar la solicitud'
            ]);
        }
    }

    public function apiCancelar($id) {
        header('Content-Type: application/json');

        $solicitud = $this->solicitudModel->getById($id);

        if (!$solicitud) {
            echo json_encode([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ]);
            return;
        }

        if ((int)$solicitud['solicitante_id'] !== (int)$_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permiso para cancelar esta solicitud'
            ]);
            return;
        }

        if ((int)$solicitud['estado_solicitud_id'] !== 1) {
            echo json_encode([
                'success' => false,
                'message' => 'La solicitud ya fue procesada'
            ]);
            return;
        }

        $result = $this->solicitudModel->updateEstado(
            $id,
            4
        );

        if ($result) {
            $this->solicitudModel->createHistorial([
                'solicitud_id' => $id,
                'estado_solicitud_id' => 4,
                'cambiado_por' => $_SESSION['user_id'],
                'comentario' => 'Solicitud cancelada'
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Solicitud cancelada exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al cancelar la solicitud'
            ]);
        }
    }
}