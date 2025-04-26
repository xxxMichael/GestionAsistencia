<?php
include ("conexion.php");

$conn = new conexion();
$con = $conn->conectar();

$sqlSelect = "SELECT e.cedula, e.nombre, e.apellido, e.telefono, e.direccion, e.hora_entrada_mnn, e.hora_salida_mnn, e.hora_entrada_tarde, e.hora_salida_tarde 
FROM empleados e";


$respuesta = $con->query($sqlSelect);
$resultado = array();

if ($respuesta->num_rows > 0) {
    while ($row = $respuesta->fetch_assoc()) {
        $resultado[] = $row;
    }
} else {
    $resultado = array("message" => "No Empleados");
}

echo json_encode($resultado);
?>