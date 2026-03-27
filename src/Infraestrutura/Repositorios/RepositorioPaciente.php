<?php

namespace Luizlins\Projeto01\Infraestrutura\Repositorios;

use DateTimeImmutable;
use Luizlins\Projeto01\Dominio\Modulos\Paciente;
use Luizlins\Projeto01\Dominio\Repositorios\RepositorioPacienteInterface;
use Luizlins\Projeto01\Infraestrutura\Persistencia\FabricaConexao;
use PDO;
use PDOStatement;

class RepositorioPaciente implements RepositorioPacienteInterface
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = FabricaConexao::criarConexao();
    }

    public function listar(): array
    {
        $sqlQuery = "SELECT * FROM pacientes;";
        $stmt = $this->conexao->query($sqlQuery);

        return $this->hidratacao($stmt);
    }

    public function inserir(Paciente $paciente): bool
    {
        $inserirQuery = "INSERT INTO pacientes (
            cpf,
            nome,
            telefone,
            data_nascimento
        ) VALUES (
            :cpf,
            :nome,
            :telefone,
            :data_nascimento
        );";

        $stmt = $this->conexao->prepare($inserirQuery);

        $sucesso = $stmt->execute([
            ':cpf' => $paciente->recuperarCpf(),
            ':nome' => $paciente->recuperarNome(),
            ':telefone' => json_encode($paciente->recuperarTelefones()),
            ':data_nascimento' => $paciente->recuperarDataNascimento()->format('Y-m-d'),
        ]);

        $paciente->definirId((int) $this->conexao->lastInsertId());

        return $sucesso;
    }

    public function deletar(Paciente $paciente): bool
    {
        $stmt = $this->conexao->prepare("DELETE FROM pacientes WHERE id = ?;");
        $stmt->bindValue(1, $paciente->recuperarId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizar(Paciente $paciente): bool
    {
        $atualizarQuery = "UPDATE pacientes
                           SET
                               cpf = :cpf,
                               nome = :nome,
                               telefone = :telefone,
                               data_nascimento = :data_nascimento
                           WHERE
                               id = :id;";

        $stmt = $this->conexao->prepare($atualizarQuery);
        $stmt->bindValue(':cpf', $paciente->recuperarCpf());
        $stmt->bindValue(':nome', $paciente->recuperarNome());
        $stmt->bindValue(':telefone', json_encode($paciente->recuperarTelefones()));
        $stmt->bindValue(':data_nascimento', $paciente->recuperarDataNascimento()->format('Y-m-d'));
        $stmt->bindValue(':id', $paciente->recuperarId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function recuperar(int $id): ?Paciente
    {
        $stmt = $this->conexao->prepare("SELECT * FROM pacientes WHERE id = ?;");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        $dadosPaciente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dadosPaciente) {
            return null;
        }

        return new Paciente(
            $dadosPaciente['id'],
            $dadosPaciente['cpf'],
            $dadosPaciente['nome'],
            json_decode($dadosPaciente['telefone'], true) ?? [],
            new DateTimeImmutable($dadosPaciente['data_nascimento'])
        );
    }

    private function hidratacao(PDOStatement $stmt): array
    {
        $listaDadosPacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $listaPacientes = [];

        foreach ($listaDadosPacientes as $paciente) {
            $listaPacientes[] = new Paciente(
                $paciente['id'],
                $paciente['cpf'],
                $paciente['nome'],
                json_decode($paciente['telefone'], true) ?? [],
                new DateTimeImmutable($paciente['data_nascimento'])
            );
        }

        return $listaPacientes;
    }
}