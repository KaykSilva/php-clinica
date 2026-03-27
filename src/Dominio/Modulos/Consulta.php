<?php

namespace Luizlins\Projeto01\Dominio\Modulos;

use DateTimeImmutable;

class Consulta
{
    public function __construct(
        private ?int $id,
        private Medico $medico,
        private Paciente $paciente,
        private DateTimeImmutable $data,
        private float $valor
    ) {}

    public function recuperarId(): ?int
    {
        return $this->id;
    }

    public function definirId(int $id): void
    {
        $this->id = $id;
    }

    public function recuperarMedico(): Medico
    {
        return $this->medico;
    }

    public function recuperarPaciente(): Paciente
    {
        return $this->paciente;
    }

    public function recuperarData(): DateTimeImmutable
    {
        return $this->data;
    }

    public function recuperarValor(): float
    {
        return $this->valor;
    }
}