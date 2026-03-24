<?php

namespace Luizlins\Projeto01\Dominio\Repositorios;

use Luizlins\Projeto01\Dominio\Modulos\Medico;

interface RepositorioMedicoInterface
{

    public function listar(): array;
    public function inserir(Medico $medico): bool;
    public function deletar(Medico $medico);
    public function atualizar(Medico $medico);
    public function recuperar(Medico $medico);

}
