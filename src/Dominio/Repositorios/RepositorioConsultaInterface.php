<?php

namespace Luizlins\Projeto01\Dominio\Repositorios;

use Luizlins\Projeto01\Dominio\Modulos\Consulta;

interface RepositorioConsultaInterface
{
    public function listar(): array;
    public function inserir(Consulta $consulta): bool;
    public function deletar(Consulta $consulta): bool;
    public function atualizar(Consulta $consulta): bool;
    public function recuperar(int $id): ?Consulta;
}