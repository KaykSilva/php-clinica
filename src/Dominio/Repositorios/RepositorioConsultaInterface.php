<?php

namespace Luizlins\Projeto01\Infraestrutura\Repositorios;

use DateTimeImmutable;
use Luizlins\Projeto01\Dominio\Modulos\Consulta;
use Luizlins\Projeto01\Dominio\Repositorios\RepositorioConsultaInterface;
use Luizlins\Projeto01\Infraestrutura\Persistencia\FabricaConexao;
use PDO;
use PDOStatement;

class RepositorioConsulta implements RepositorioConsultaInterface
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = FabricaConexao::criarConexao();
    }

    public function listar(): array
    {
        $sqlQuery = "SELECT
                        c.id as consulta_id,
                        c.data,
                        c.valor,

                        m.id as medico_id,
                        m.crm as medico_crm,
                        m.nome as medico_nome,
                        m.especialidade as medico_especialidade,

                        p.id as paciente_id,
                        p.cpf as paciente_cpf,
                        p.nome as paciente_nome,
                        p.telefone as paciente_telefone,
                        p.data_nascimento as paciente_data_nascimento
                    FROM consultas c
                    INNER JOIN medicos m ON m.id = c.medico_id
                    INNER JOIN pacientes p ON p.id = c.paciente_id;";

        $stmt = $this->conexao->query($sqlQuery);

        return $this->hidratacao($stmt);
    }

    public function inserir(Consulta $consulta): bool
    {
        $inserirQuery = "INSERT INTO consultas (
            medico_id,
            paciente_id,
            data,
            valor
        ) VALUES (
            :medico_id,
            :paciente_id,
            :data,
            :valor
        );";

        $stmt = $this->conexao->prepare($inserirQuery);

        $sucesso = $stmt->execute([
            ':medico_id' => $consulta->recuperarMedico()->recuperarId(),
            ':paciente_id' => $consulta->recuperarPaciente()->recuperarId(),
            ':data' => $consulta->recuperarData()->format('Y-m-d H:i:s'),
            ':valor' => $consulta->recuperarValor(),
        ]);

        $consulta->definirId((int) $this->conexao->lastInsertId());

        return $sucesso;
    }

    public function deletar(Consulta $consulta): bool
    {
        $stmt = $this->conexao->prepare("DELETE FROM consultas WHERE id = ?;");
        $stmt->bindValue(1, $consulta->recuperarId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizar(Consulta $consulta): bool
    {
        $atualizarQuery = "UPDATE consultas
                           SET
                               medico_id = :medico_id,
                               paciente_id = :paciente_id,
                               data = :data,
                               valor = :valor
                           WHERE
                               id = :id;";

        $stmt = $this->conexao->prepare($atualizarQuery);
        $stmt->bindValue(':medico_id', $consulta->recuperarMedico()->recuperarId(), PDO::PARAM_INT);
        $stmt->bindValue(':paciente_id', $consulta->recuperarPaciente()->recuperarId(), PDO::PARAM_INT);
        $stmt->bindValue(':data', $consulta->recuperarData()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':valor', $consulta->recuperarValor());
        $stmt->bindValue(':id', $consulta->recuperarId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function recuperar(int $id): ?Consulta
    {
        $stmt = $this->conexao->prepare("SELECT
                                            c.id as consulta_id,
                                            c.data,
                                            c.valor,

                                            m.id as medico_id,
                                            m.crm as medico_crm,
                                            m.nome as medico_nome,
                                            m.especialidade as medico_especialidade,

                                            p.id as paciente_id,
                                            p.cpf as paciente_cpf,
                                            p.nome as paciente_nome,
                                            p.telefone as paciente_telefone,
                                            p.data_nascimento as paciente_data_nascimento
                                        FROM consultas c
                                        INNER JOIN medicos m ON m.id = c.medico_id
                                        INNER JOIN pacientes p ON p.id = c.paciente_id
                                        WHERE c.id = ?;");

        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        $dadosConsulta = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dadosConsulta) {
            return null;
        }

        $medico = new \Luizlins\Projeto01\Dominio\Modulos\Medico(
            $dadosConsulta['medico_id'],
            $dadosConsulta['medico_crm'],
            $dadosConsulta['medico_nome'],
            $dadosConsulta['medico_especialidade']
        );

        $paciente = new \Luizlins\Projeto01\Dominio\Modulos\Paciente(
            $dadosConsulta['paciente_id'],
            $dadosConsulta['paciente_cpf'],
            $dadosConsulta['paciente_nome'],
            json_decode($dadosConsulta['paciente_telefone'], true) ?? [],
            new DateTimeImmutable($dadosConsulta['paciente_data_nascimento'])
        );

        return new Consulta(
            $dadosConsulta['consulta_id'],
            $medico,
            $paciente,
            new DateTimeImmutable($dadosConsulta['data']),
            (float) $dadosConsulta['valor']
        );
    }

    private function hidratacao(PDOStatement $stmt): array
    {
        $listaDadosConsultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $listaConsultas = [];

        foreach ($listaDadosConsultas as $consulta) {
            $medico = new \Luizlins\Projeto01\Dominio\Modulos\Medico(
                $consulta['medico_id'],
                $consulta['medico_crm'],
                $consulta['medico_nome'],
                $consulta['medico_especialidade']
            );

            $paciente = new \Luizlins\Projeto01\Dominio\Modulos\Paciente(
                $consulta['paciente_id'],
                $consulta['paciente_cpf'],
                $consulta['paciente_nome'],
                json_decode($consulta['paciente_telefone'], true) ?? [],
                new DateTimeImmutable($consulta['paciente_data_nascimento'])
            );

            $listaConsultas[] = new Consulta(
                $consulta['consulta_id'],
                $medico,
                $paciente,
                new DateTimeImmutable($consulta['data']),
                (float) $consulta['valor']
            );
        }

        return $listaConsultas;
    }
}