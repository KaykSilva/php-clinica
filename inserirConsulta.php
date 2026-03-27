<?php

use Luizlins\Projeto01\Dominio\Modulos\Consulta;
use Luizlins\Projeto01\Infraestrutura\Repositorios\RepositorioConsulta;
use Luizlins\Projeto01\Infraestrutura\Repositorios\RepositorioMedico;
use Luizlins\Projeto01\Infraestrutura\Repositorios\RepositorioPaciente;

require_once "vendor/autoload.php";

$repositorioMedico = new RepositorioMedico();
$repositorioPaciente = new RepositorioPaciente();
$repositorioConsulta = new RepositorioConsulta();

$medico = $repositorioMedico->recuperar(1);
$paciente = $repositorioPaciente->recuperar(1);

if (!$medico || !$paciente) {
    die("Médico ou paciente não encontrado." . PHP_EOL);
}

$consulta = new Consulta(
    null,
    $medico,
    $paciente,
    new DateTimeImmutable("2026-03-27 15:30:00"),
    250.00
);

$resposta = $repositorioConsulta->inserir($consulta);

var_dump($resposta);
var_dump($consulta->recuperarId());