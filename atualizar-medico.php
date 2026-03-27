<?php

use Luizlins\Projeto01\Dominio\Modulos\Paciente;
use Luizlins\Projeto01\Infraestrutura\Repositorios\RepositorioPaciente;

require_once "vendor/autoload.php";

$paciente = new Paciente(
    1,
    "123.456.789-00",
    "Kayk Atualizado",
    ["(99) 99999-9999"],
    new DateTimeImmutable("2000-05-10")
);

$repositorio = new RepositorioPaciente();

$resposta = $repositorio->atualizar($paciente);

var_dump($resposta);