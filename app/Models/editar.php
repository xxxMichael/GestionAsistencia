<?php
header('Content-Type: application/json');
include ("conexion.php");

$conn = new conexion();
$con = $conn->conectar();

if (!$con) {
    echo json_encode(["success" => false, "message" => "Error en la conexión a la base de datos: " . $conn->error]);
    exit();
}

// Obtener datos del POST
$cedula = $_POST['emp_cedula'];
$nombre = $_POST['emp_nombre'];
$apellido = $_POST['emp_apellido'];
$direccion = $_POST['emp_direccion'];
$telefono = $_POST['emp_telefono'];
$hora_entrada_mnn = $_POST['emp_hora_entrada_mnn'];
$hora_salida_mnn = $_POST['emp_hora_salida_mnn'];
$hora_entrada_tarde = $_POST['emp_hora_entrada_tarde'];
$hora_salida_tarde = $_POST['emp_hora_salida_tarde'];

// Construir consulta SQL
$sqlEdit = "UPDATE empleados SET nombre='$nombre', apellido='$apellido', telefono='$telefono', direccion='$direccion', hora_entrada_mnn='$hora_entrada_mnn', hora_salida_mnn='$hora_salida_mnn', hora_entrada_tarde='$hora_entrada_tarde', hora_salida_tarde='$hora_salida_tarde' WHERE cedula = '$cedula'";

// Ejecutar consulta SQL
if ($con->query($sqlEdit) === TRUE) {
    echo json_encode(["success" => true, "message" => "Datos actualizados"]);
} else {
    echo json_encode(["success" => false, "message" => "Actualización errónea: " . $con->error]);
}
?>
