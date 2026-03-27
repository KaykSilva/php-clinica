<?php

use Luizlins\Projeto01\Infraestrutura\Repositorios\RepositorioConsulta;

require_once "vendor/autoload.php";

$repositorioConsulta = new RepositorioConsulta();
$consultas = $repositorioConsulta->listar();

foreach ($consultas as $consulta) {
    echo "ID: " . $consulta->recuperarId() . PHP_EOL;
    echo "Médico: " . $consulta->recuperarMedico()->recuperarNome() . PHP_EOL;
    echo "Paciente: " . $consulta->recuperarPaciente()->recuperarNome() . PHP_EOL;
    echo "Data: " . $consulta->recuperarData()->format('d/m/Y H:i') . PHP_EOL;
    echo "Valor: R$ " . number_format($consulta->recuperarValor(), 2, ',', '.') . PHP_EOL;
    echo str_repeat("-", 30) . PHP_EOL;
}