<?php

class IntercambioController extends Controller
{
    private $intercambioModel;
    private $solicitudModel;
    private $creditoModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
        }

        $this->intercambioModel = $this->model('Intercambio');
        $this->solicitudModel = $this->model('Solicitud');
        $this->creditoModel = $this->model('Credito');
    }

    public function index()
    {
        $this->view('intercambios/index');
    }

    public function apiList()
    {
        header('Content-Type: application/json');

        $intercambios = $this->intercambioModel->getByUsuario(
            $_SESSION['user_id']
        );

        echo json_encode($intercambios);
    }

    public function apiCompletar($id)
    {
        header('Content-Type: application/json');

        $intercambio = $this->intercambioModel->getById($id);

        if (!$intercambio) {
            echo json_encode([
                'success' => false,
                'message' => 'Intercambio no encontrado'
            ]);
            return;
        }

        $esSolicitante =
            (int) $intercambio['solicitante_id'] ===
            (int) $_SESSION['user_id'];

        $esPropietario =
            (int) $intercambio['propietario_id'] ===
            (int) $_SESSION['user_id'];

        if (!$esSolicitante && !$esPropietario) {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permiso para completar este intercambio'
            ]);
            return;
        }

        if ((int) $intercambio['estado_intercambio_id'] !== 1) {
            echo json_encode([
                'success' => false,
                'message' => 'El intercambio ya fue procesado'
            ]);
            return;
        }

        $creditos = (float) (
            $intercambio['creditos_ofrecidos'] ?? 0
        );

        if (
            $creditos > 0 &&
            !$this->creditoModel->tieneSaldo(
                (int) $intercambio['solicitante_id'],
                $creditos
            )
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'El solicitante no tiene créditos suficientes'
            ]);
            return;
        }

        $intercambioCompletado =
            $this->intercambioModel->completar($id);

        $solicitudCompletada =
            $this->intercambioModel->completarSolicitud(
                $intercambio['solicitud_id']
            );

        $publicacionFinalizada =
            $this->intercambioModel->finalizarPublicacion(
                $intercambio['publicacion_id']
            );

        $publicacionOfrecidaFinalizada = true;

        if (!empty($intercambio['publicacion_ofrecida_id'])) {
            $publicacionOfrecidaFinalizada =
                $this->intercambioModel->finalizarPublicacion(
                    $intercambio['publicacion_ofrecida_id']
                );
        }

        if (
            !$intercambioCompletado ||
            !$solicitudCompletada ||
            !$publicacionFinalizada ||
            !$publicacionOfrecidaFinalizada
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al completar el intercambio'
            ]);
            return;
        }

        if ($creditos > 0) {
            $debito =
                $this->creditoModel->registrarMovimiento(
                    (int) $intercambio['solicitante_id'],
                    (int) $id,
                    3,
                    -$creditos,
                    'Débito por intercambio'
                );

            $credito =
                $this->creditoModel->registrarMovimiento(
                    (int) $intercambio['propietario_id'],
                    (int) $id,
                    2,
                    $creditos,
                    'Crédito por intercambio'
                );

            if (!$debito || !$credito) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al registrar los créditos'
                ]);
                return;
            }
        }

        $this->solicitudModel->createHistorial([
            'solicitud_id' => $intercambio['solicitud_id'],
            'estado_solicitud_id' => 6,
            'cambiado_por_id' => $_SESSION['user_id'],
            'comentario' => 'Intercambio completado'
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Intercambio completado exitosamente'
        ]);
    }
}