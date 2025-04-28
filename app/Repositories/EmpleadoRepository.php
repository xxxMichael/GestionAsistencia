<?php

require_once '../Database/conexion.php';

class EmpleadosRepository {
    private $conexion;

    public function __construct() {
        $db = new conexion();
        $this->conexion = $db->conectar();
    }

    public function getAll() {
        $sql = "SELECT cedula, nombre, apellido, telefono, direccion, hora_entrada_mnn, hora_salida_mnn, hora_entrada_tarde, hora_salida_tarde FROM empleados";
        $result = $this->conexion->query($sql);

        $empleados = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $empleados[] = $row;
            }
        }

        return $empleados;
    }

    public function insert($data) {
        $cedula = $data['emp_cedula'];
        $nombre = $data['emp_nombre'];
        $apellido = $data['emp_apellido'];
        $telefono = $data['emp_telefono'];
        $direccion = $data['emp_direccion'];
        $horaEntradaMnn = $data['emp_hora_entrada_mnn'];
        $horaSalidaMnn = $data['emp_hora_salida_mnn'];
        $horaEntradaTarde = $data['emp_hora_entrada_tarde'];
        $horaSalidaTarde = $data['emp_hora_salida_tarde'];

        $sqlEmpleado = "INSERT INTO empleados (cedula, nombre, apellido, telefono, direccion, hora_entrada_mnn, hora_salida_mnn, hora_entrada_tarde, hora_salida_tarde)
                        VALUES ('$cedula', '$nombre', '$apellido', '$telefono', '$direccion', '$horaEntradaMnn', '$horaSalidaMnn', '$horaEntradaTarde', '$horaSalidaTarde')";

        $sqlUsuario = "INSERT INTO usuarios (usuario, contrasenia, rol) VALUES ('$cedula', '$cedula', 'empleado')";

        if ($this->conexion->query($sqlEmpleado) === TRUE && $this->conexion->query($sqlUsuario) === TRUE) {
            return ["success" => true, "message" => "Empleado guardado exitosamente."];
        } else {
            return ["success" => false, "message" => "Error al guardar empleado: " . $this->conexion->error];
        }
    }

    public function update($data) {
        $cedula = $data['emp_cedula'];
        $nombre = $data['emp_nombre'];
        $apellido = $data['emp_apellido'];
        $telefono = $data['emp_telefono'];
        $direccion = $data['emp_direccion'];
        $horaEntradaMnn = $data['emp_hora_entrada_mnn'];
        $horaSalidaMnn = $data['emp_hora_salida_mnn'];
        $horaEntradaTarde = $data['emp_hora_entrada_tarde'];
        $horaSalidaTarde = $data['emp_hora_salida_tarde'];

        $sql = "UPDATE empleados 
                SET nombre='$nombre', apellido='$apellido', telefono='$telefono', direccion='$direccion', 
                    hora_entrada_mnn='$horaEntradaMnn', hora_salida_mnn='$horaSalidaMnn', 
                    hora_entrada_tarde='$horaEntradaTarde', hora_salida_tarde='$horaSalidaTarde' 
                WHERE cedula='$cedula'";

        if ($this->conexion->query($sql) === TRUE) {
            return ["success" => true, "message" => "Empleado actualizado exitosamente."];
        } else {
            return ["success" => false, "message" => "Error al actualizar empleado: " . $this->conexion->error];
        }
    }

    public function delete($cedula) {
        $sql = "DELETE FROM empleados WHERE cedula='$cedula'";

        if ($this->conexion->query($sql) === TRUE) {
            return ["success" => true, "message" => "Empleado eliminado exitosamente."];
        } else {
            return ["success" => false, "message" => "Error al eliminar empleado: " . $this->conexion->error];
        }
    }
}
?>
