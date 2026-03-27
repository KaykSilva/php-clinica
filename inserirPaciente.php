<?php

use Luizlins\Projeto01\Dominio\Modulos\Paciente;
use Luizlins\Projeto01\Infraestrutura\Repositorios\RepositorioPaciente;

require_once "vendor/autoload.php";

$paciente = new Paciente(
    null,
    "123.456.789-00",
    "Kayk Silva",
    ["(99) 99999-9999", "(99) 98888-8888"],
    new DateTimeImmutable("2004-05-10")
);

$repositorioPaciente = new RepositorioPaciente();
$resposta = $repositorioPaciente->inserir($paciente);

var_dump($resposta);
var_dump($paciente->recuperarId());