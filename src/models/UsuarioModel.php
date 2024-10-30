<?php

require_once dirname(__DIR__) . '/core/Database.php';

class UsuarioModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO usuarios (pessoa_id, nivel_id, usuario_senha, usuario_ativo) VALUES (:pessoa_id, :nivel_id, :usuario_senha, :usuario_ativo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':pessoa_id', $dados['pessoa_id'], PDO::PARAM_INT);
        $stmt->bindParam(':nivel_id', $dados['nivel_id'], PDO::PARAM_INT);
        $stmt->bindParam(':usuario_senha', password_hash($dados['usuario_senha'], PASSWORD_DEFAULT), PDO::PARAM_STR); // Armazenar senha de forma segura.
        $stmt->bindParam(':usuario_ativo', $dados['usuario_ativo'], PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE usuarios SET nivel_id = :nivel_id, usuario_senha = :usuario_senha, usuario_ativo = :usuario_ativo WHERE pessoa_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nivel_id', $dados['nivel_id'], PDO::PARAM_INT);
        $stmt->bindParam(':usuario_senha', password_hash($dados['usuario_senha'], PASSWORD_DEFAULT), PDO::PARAM_STR); // Armazenar senha de forma segura.
        $stmt->bindParam(':usuario_ativo', $dados['usuario_ativo'], PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function listar() {
        $query = "SELECT * FROM usuarios";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id) {
        $query = "SELECT * FROM usuarios WHERE pessoa_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function apagar($id) {
        $query = "DELETE FROM usuarios WHERE pessoa_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
