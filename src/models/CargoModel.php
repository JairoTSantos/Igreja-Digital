<?php

require_once dirname(__DIR__) . '/core/Database.php';

class CargoModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO cargos (cargo_nome, cargo_descricao) VALUES (:cargo_nome, :cargo_descricao)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cargo_nome', $dados['cargo_nome'], PDO::PARAM_STR);
        $stmt->bindParam(':cargo_descricao', $dados['cargo_descricao'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE cargos SET cargo_nome = :cargo_nome, cargo_descricao = :cargo_descricao WHERE cargo_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cargo_nome', $dados['cargo_nome'], PDO::PARAM_STR);
        $stmt->bindParam(':cargo_descricao', $dados['cargo_descricao'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function listar($termo, $colunaOrdenacao, $ordem) {

        $colunasPermitidas = ['cargo_nome', 'cargo_id', 'cargo_adicionado_em'];
        $ordensPermitidas = ['ASC', 'DESC'];

        $colunaOrdenacao = in_array($colunaOrdenacao, $colunasPermitidas) ? $colunaOrdenacao : 'cargo_nome';
        $ordem = in_array($ordem, $ordensPermitidas) ? $ordem : 'ASC';

        $query = "SELECT * FROM cargos";
        if (!empty($termo)) {
            $query .= " WHERE cargo_nome LIKE :termo";
        }
        $query .= " ORDER BY {$colunaOrdenacao} {$ordem}";

        $stmt = $this->db->prepare($query);

        if (!empty($termo)) {
            $stmt->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id) {
        $query = "SELECT * FROM cargos WHERE cargo_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function apagar($id) {
        $query = "DELETE FROM cargos WHERE cargo_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
