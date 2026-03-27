<?php

use Luizlins\Projeto01\Infraestrutura\Repositorios\RepositorioPaciente;

require_once "vendor/autoload.php";

$repositorioPaciente = new RepositorioPaciente();
$resposta = $repositorioPaciente->listar();

var_dump($resposta);