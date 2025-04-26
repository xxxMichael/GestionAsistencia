<?php
header('Content-Type: application/json');
include ("conexion.php");

$conn = new conexion();
$con = $conn->conectar();
$cedula = $_POST['cedula'];

$sqlDelete = "DELETE FROM empleados WHERE cedula = '$cedula'";

$response = array();

if ($con->query($sqlDelete) === TRUE) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['errorMsg'] = "Fallo: " . $con->error;
}

echo json_encode($response);
?>