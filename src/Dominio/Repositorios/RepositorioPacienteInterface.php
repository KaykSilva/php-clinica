<?php

namespace Luizlins\Projeto01\Dominio\Repositorios;

use Luizlins\Projeto01\Dominio\Modulos\Paciente;

interface RepositorioPacienteInterface
{
    public function listar(): array;

    public function inserir(Paciente $paciente): bool;

    public function deletar(Paciente $paciente): bool;

    public function atualizar(Paciente $paciente): bool;

    public function recuperar(int $id): ?Paciente;
}