<?php
require_once '../Repositories/EmpleadoRepository.php';

header('Content-Type: application/json');

$repo = new EmpleadosRepository();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $empleados = $repo->getAll();
        echo json_encode($empleados);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $repo->insert($data);
        echo json_encode($result);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $repo->update( $data);
        echo json_encode($result);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $cedula = $data['cedula'];
        $result = $repo->delete($cedula);
        echo json_encode($result);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        break;
}
?>
