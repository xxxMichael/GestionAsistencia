<?php
require_once '../Repositories/AsistenciaRepository.php';

class AsistenciaService {
    private $repository;

    public function __construct() {
        $this->repository = new AsistenciaRepository();
    }

    public function obtenerAsistencia($cedula, $fecha) {
        return $this->repository->obtenerAsistenciaPorFechaYCedula($cedula, $fecha);
    }

    public function registrarAsistencia($tipoRegistro, $hora, $cedula) {
        $fecha = date('Y-m-d');
        $campo = $this->mapearCampo($tipoRegistro);

        if (!$campo) {
            return ['success' => false, 'error' => 'Tipo de registro no válido'];
        }

        $existe = $this->repository->existeAsistencia($cedula, $fecha);

        if ($existe) {
            $ok = $this->repository->actualizarAsistencia($cedula, $fecha, $campo, $hora);
        } else {
            $ok = $this->repository->insertarAsistencia($cedula, $fecha, $campo, $hora);
        }

        return $ok ? ['success' => true, 'fec' => $fecha] : ['success' => false, 'error' => 'Error al guardar asistencia'];
    }

    private function mapearCampo($tipoRegistro) {
        return match ($tipoRegistro) {
            'entradaManana' => 'hora_entrada_mnn',
            'salidaManana' => 'hora_salida_mnn',
            'entradaTarde' => 'hora_entrada_tarde',
            'salidaTarde' => 'hora_salida_tarde',
            default => null
        };
    }
    public function obtenerHorasEmpleado($cedula) {
        return $this->repository->obtenerHorasEmpleado($cedula);
    }
}
