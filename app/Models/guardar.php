<?php
header('Content-Type: application/json');
include ("conexion.php");

$conn = new conexion();
$con = $conn->conectar();
$cedula = $_POST['emp_cedula'];
$nombre = $_POST['emp_nombre'];
$apellido = $_POST['emp_apellido'];
$direccion = $_POST['emp_direccion'];
$telefono = $_POST['emp_telefono'];
$hora_entrada_mnn = $_POST['emp_hora_entrada_mnn'];
$hora_salida_mnn = $_POST['emp_hora_salida_mnn'];
$hora_entrada_tarde_mnn = $_POST['emp_hora_entrada_tarde'];
$hora_salida_tarde = $_POST['emp_hora_salida_tarde'];

$sqlInsertEmpleado = "INSERT INTO empleados (cedula, nombre, apellido, telefono, direccion, hora_entrada_mnn, hora_salida_mnn, hora_entrada_tarde, hora_salida_tarde) VALUES('$cedula', '$nombre', '$apellido', '$telefono', '$direccion', '$hora_entrada_mnn', '$hora_salida_mnn', '$hora_entrada_tarde_mnn', '$hora_salida_tarde')";

$sqlInsertUsuario = "INSERT INTO usuarios (usuario, contrasenia, rol) VALUES('$cedula', '$cedula', 'empleado')";

if ($con->query($sqlInsertEmpleado) === TRUE && $con->query($sqlInsertUsuario) === TRUE) {
    echo json_encode(["success" => true, "message" => "Se guardó correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Fallo al guardar: " . $con->error]);
}
?>