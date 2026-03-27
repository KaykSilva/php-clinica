<?php

use Luizlins\Projeto01\Infraestrutura\Repositorios\RepositorioPaciente;

require_once "vendor/autoload.php";

$repositorio = new RepositorioPaciente();

$paciente = $repositorio->recuperar(1);

if (!$paciente) {
    echo "Paciente não encontrado";
    return;
}

echo "ID: " . $paciente->recuperarId() . PHP_EOL;
echo "CPF: " . $paciente->recuperarCpf() . PHP_EOL;
echo "Nome: " . $paciente->recuperarNome() . PHP_EOL;
echo "Telefone: " . implode(", ", $paciente->recuperarTelefones()) . PHP_EOL;
echo "Nascimento: " . $paciente->recuperarDataNascimento()->format('d/m/Y') . PHP_EOL;