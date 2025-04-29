<?php
require_once '../Services/AsistenciaService.php';

$service = new AsistenciaService();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);

    if (!$datos) {
        $datos = $_POST;
    }

    if (!isset($datos['action'])) {
        echo json_encode(['error' => 'No se especificó la acción']);
        exit;
    }

    switch ($datos['action']) {
        case 'obtenerHoras':
            if (!isset($datos['cedula'])) {
                echo json_encode(['error' => 'Cédula no proporcionada']);
                exit;
            }
            echo $service->obtenerHorasEmpleado($datos['cedula']);
            break;

        case 'consultarAsistencia':
            if (!isset($datos['cedula'], $datos['fecha_actual'])) {
                echo json_encode(['error' => 'Faltan datos para consultar asistencia']);
                exit;
            }

            $asistencia = $service->obtenerAsistencia(
                $datos['cedula'],
                $datos['fecha_actual']
            );

            echo json_encode($asistencia ?: ['error' => 'No se encontraron registros']);
            break;

        case 'registrarAsistencia':
            if (!isset($datos['tipoRegistro'], $datos['hora'], $datos['cedulaEmpleado'])) {
                echo json_encode(['error' => 'Faltan datos para registrar asistencia']);
                exit;
            }

            $resultado = $service->registrarAsistencia(
                $datos['tipoRegistro'],
                $datos['hora'],
                $datos['cedulaEmpleado']
            );

            echo json_encode($resultado);
            break;

        case 'registrarMulta':
            // puedes implementar esto luego si lo necesitas
            echo json_encode(['mensaje' => 'Funcionalidad de multa aún no implementada']);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
