<?php

namespace Luizlins\Projeto01\Dominio\Repositorios;

use Luizlins\Projeto01\Dominio\Modulos\Medico;

interface RepositorioMedicoInterface
{
    /**
     * @return Medico[]
     */
    public function listar(): array;

    public function inserir(Medico $medico): bool;

    public function deletar(Medico $medico): bool;

    public function atualizar(Medico $medico): bool;

    public function recuperar(int $id): ?Medico;
}