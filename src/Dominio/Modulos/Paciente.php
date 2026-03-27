<?php

namespace Luizlins\Projeto01\Dominio\Modulos;

use DateTimeImmutable;

class Paciente {

    public function __construct(
        private ?int $id,
        private string $cpf,
        private string $nome,
        private array $telefones,
        private DateTimeImmutable $dataNascimento
    ) {}

    public function recuperarId(): ?int
    {
        return $this->id;
    }

    public function definirId(int $id): void
    {
        $this->id = $id;
    }

    public function recuperarCpf(): string
    {
        return $this->cpf;
    }

    public function recuperarNome(): string
    {
        return strtoupper($this->nome);
    }

    public function recuperarTelefones(): array
    {
        return $this->telefones;
    }

    public function recuperarDataNascimento(): DateTimeImmutable
    {
        return $this->dataNascimento;
    }

    public function recuperarDataNascimentoFormatada(): string
    {
        return $this->dataNascimento->format('Y-m-d');
    }
}