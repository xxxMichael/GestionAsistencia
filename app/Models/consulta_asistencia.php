<?php
require_once 'conexion.php';
if (!isset($_POST['fecha_actual']) || !isset($_POST['cedula'])) {
    echo json_encode(['error' => 'No se proporcionó la fecha actual o la']);
    exit;
}

// Obtener fecha actual (día y mes) y username desde la solicitud POST
$fecha_actual = $_POST['fecha_actual'];
$username = $_POST['cedula'];

// Obtener la hora actual

// Instancia de la clase de conexión
$conexion = new conexion();
$conn = $conexion->conectar();

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Consulta para obtener la asistencia del usuario para la fecha actual
$query = "SELECT hora_entrada_mnn, hora_salida_mnn, hora_entrada_tarde, hora_salida_tarde 
          FROM asistencia 
          WHERE ced_emp_per = ? AND fecha = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ss', $username, $fecha_actual);

// Ejecutar la consulta
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_store_result($stmt);
    
    // Vincular variables de resultado
    mysqli_stmt_bind_result($stmt, $hora_entrada_mnn, $hora_salida_mnn, $hora_entrada_tarde, $hora_salida_tarde);

    // Obtener resultados
    if (mysqli_stmt_fetch($stmt)) {
        // Verificar si la hora actual ya superó la hora de salida más 10 minutos
      
        
        // Preparar el resultado para enviar como JSON
        $result = [
            'hora_entrada_mnn' => $hora_entrada_mnn,
            'hora_salida_mnn' =>  $hora_salida_mnn,
            'hora_entrada_tarde' => $hora_entrada_tarde,
            'hora_salida_tarde' => $hora_salida_tarde
        ];
        
        echo json_encode($result);
    } else {
        // No se encontraron datos para la fecha y usuario especificados
        echo json_encode(['error' => 'No se encontraron registros de asistencia para la fecha actual y el usuario']);
    }
} else {
    // Error en la ejecución de la consulta
    echo json_encode(['error' => 'Error al consultar la asistencia']);
}

// Cerrar la declaración y conexión
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
