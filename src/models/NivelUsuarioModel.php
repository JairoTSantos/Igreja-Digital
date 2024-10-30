<?php

require_once dirname(__DIR__) . '/core/Database.php';

class NivelUsuarioModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO niveis_usuarios (nivel_nome, nivel_descricao) VALUES (:nivel_nome, :nivel_descricao)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nivel_nome', $dados['nivel_nome'], PDO::PARAM_STR);
        $stmt->bindParam(':nivel_descricao', $dados['nivel_descricao'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE niveis_usuarios SET nivel_nome = :nivel_nome, nivel_descricao = :nivel_descricao WHERE nivel_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nivel_nome', $dados['nivel_nome'], PDO::PARAM_STR);
        $stmt->bindParam(':nivel_descricao', $dados['nivel_descricao'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function listar() {
        $query = "SELECT * FROM niveis_usuarios ORDER BY nivel_nome ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id) {
        $query = "SELECT * FROM niveis_usuarios WHERE nivel_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function apagar($id) {
        $query = "DELETE FROM niveis_usuarios WHERE nivel_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
