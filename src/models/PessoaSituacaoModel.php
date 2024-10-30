<?php

require_once dirname(__DIR__) . '/core/Database.php';

class PessoaSituacaoModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO pessoas_situacoes (situacao_nome, situacao_descricao) VALUES (:situacao_nome, :situacao_descricao)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':situacao_nome', $dados['situacao_nome'], PDO::PARAM_STR);
        $stmt->bindParam(':situacao_descricao', $dados['situacao_descricao'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE pessoas_situacoes SET situacao_nome = :situacao_nome, situacao_descricao = :situacao_descricao WHERE situacao_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':situacao_nome', $dados['situacao_nome'], PDO::PARAM_STR);
        $stmt->bindParam(':situacao_descricao', $dados['situacao_descricao'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function listar() {
        $query = "SELECT * FROM pessoas_situacoes ORDER BY situacao_nome ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id) {
        $query = "SELECT * FROM pessoas_situacoes WHERE situacao_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function apagar($id) {
        $query = "DELETE FROM pessoas_situacoes WHERE situacao_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
