<?php
require_once  '../Services/MultaService.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input = file_get_contents('php://input');
$data  = json_decode($input, true) ?: $_POST;

$valor         = $data['valor'] ?? null;
$tipoMulta     = $data['tipomulta'] ?? null;
$cedulaEmpleado= $data['cedulaEmpleado'] ?? null;

if ($valor === null || !$tipoMulta || !$cedulaEmpleado) {
    echo json_encode(['error' => 'Faltan parámetros']);
    exit;
}

$service = new MultaService();
$result  = $service->registrarMulta((float)$valor, $tipoMulta, $cedulaEmpleado);
echo json_encode($result);
