<?php

require_once dirname(__DIR__) . '/core/Database.php';

class FamiliaModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO familia (familia_nome) VALUES (:familia_nome)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':familia_nome', $dados['familia_nome'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE familia SET familia_nome = :familia_nome WHERE familia_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':familia_nome', $dados['familia_nome'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function listar2() {
        $query = "SELECT * FROM familia ORDER BY familia_nome ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function listar($termo, $colunaOrdenacao, $ordem) {

        $colunasPermitidas = ['familia_nome', 'familia_id', 'familia_adicionada_em'];
        $ordensPermitidas = ['ASC', 'DESC'];

        $colunaOrdenacao = in_array($colunaOrdenacao, $colunasPermitidas) ? $colunaOrdenacao : 'familia_nome';
        $ordem = in_array($ordem, $ordensPermitidas) ? $ordem : 'ASC';

        $query = "SELECT * FROM familia";
        if (!empty($termo)) {
            $query .= " WHERE familia_nome LIKE :termo";
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
        $query = "SELECT * FROM familia WHERE familia_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function apagar($id) {
        $query = "DELETE FROM familia WHERE familia_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
