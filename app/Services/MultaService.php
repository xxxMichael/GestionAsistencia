<?php
require_once '../Repositories/MultaRepository.php';

class MultaService
{
    private $repo;

    public function __construct()
    {
        $this->repo = new MultaRepository();
    }

    /**
     * Registra una multa, aplicando la lógica especial de inasistencia.
     * @return array Mensaje de resultado o error.
     */
    public function registrarMulta(float $valor, string $tipo, string $cedula): array
    {
        $fecha = date('Y-m-d');
        $idAsis = $this->repo->getAsistenciaId($cedula, $fecha);
        if (is_null($idAsis)) {
            return ['error' => 'No se encontró el registro de asistencia para hoy'];
        }

        if ($tipo === 'Inasistencia') {
            if ($this->repo->existsInasistencia($idAsis)) {
                return ['message' => 'Ya existe una multa por inasistencia para este día.'];
            }
            // Sumar valores previos y eliminarlos
            $sumPrev = $this->repo->sumAtrasoSalida($idAsis);
            $this->repo->deleteAtrasoSalida($idAsis);
            $this->repo->updateEmpleadoSalary($cedula, +$sumPrev);

            // Insertar multa de inasistencia y restar al sueldo
            $this->repo->insertMulta($idAsis, 'Inasistencia', $valor);
            $this->repo->updateEmpleadoSalary($cedula, -$valor);

            return ['message' => 'Multa de inasistencia registrada y sueldo actualizado.'];
        }

        // Caso genérico (Atraso, Salida Temprana, etc.)
        $this->repo->insertMulta($idAsis, $tipo, $valor);
        $this->repo->updateEmpleadoSalary($cedula, -$valor);

        return ['message' => 'Multa registrada correctamente y sueldo actualizado.'];
    }
}
