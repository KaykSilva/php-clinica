<?php

class Medico {

    private string $crm_medico;
    private string $nome_medico;
    private string $especialidade;

    function __construct(string $crm, string $nome, string $especialidade) {
        $this->crm_medico = $crm;
        $this->nome_medico = $nome;
        $this->especialidade = $especialidade;
    }

}