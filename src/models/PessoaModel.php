<?php

require_once dirname(__DIR__) . '/core/Database.php';

class PessoaModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO pessoas (
            pessoa_nome, pessoa_cpf, pessoa_email, pessoa_aniversario, pessoa_foto,
            pessoa_familia, pessoa_endereco, pessoa_municipio, pessoa_estado,
            pessoa_telefone_celular, pessoa_telefone_fixo, pessoa_instagram, pessoa_facebook,
            pessoa_data_conversao, pessoa_data_batismo, pessoa_batizada_local,
            pessoa_cargo, pessoa_situacao, pessoa_informacoes
        ) VALUES (
            :pessoa_nome, :pessoa_cpf, :pessoa_email, :pessoa_aniversario, :pessoa_foto,
            :pessoa_familia, :pessoa_endereco, :pessoa_municipio, :pessoa_estado,
            :pessoa_telefone_celular, :pessoa_telefone_fixo, :pessoa_instagram, :pessoa_facebook,
            :pessoa_data_conversao, :pessoa_data_batismo, :pessoa_batizada_local,
            :pessoa_cargo, :pessoa_situacao, :pessoa_informacoes
        )";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':pessoa_nome', $dados['pessoa_nome']);
        $stmt->bindValue(':pessoa_cpf', $dados['pessoa_cpf']);
        $stmt->bindValue(':pessoa_email', $dados['pessoa_email']);
        $stmt->bindValue(':pessoa_aniversario', $dados['pessoa_aniversario']);
        $stmt->bindValue(':pessoa_foto', $dados['pessoa_foto']);
        $stmt->bindValue(':pessoa_familia', $dados['pessoa_familia']);
        $stmt->bindValue(':pessoa_endereco', $dados['pessoa_endereco']);
        $stmt->bindValue(':pessoa_municipio', $dados['pessoa_municipio']);
        $stmt->bindValue(':pessoa_estado', $dados['pessoa_estado']);
        $stmt->bindValue(':pessoa_telefone_celular', $dados['pessoa_telefone_celular']);
        $stmt->bindValue(':pessoa_telefone_fixo', $dados['pessoa_telefone_fixo']);
        $stmt->bindValue(':pessoa_instagram', $dados['pessoa_instagram']);
        $stmt->bindValue(':pessoa_facebook', $dados['pessoa_facebook']);
        $stmt->bindValue(':pessoa_data_conversao', $dados['pessoa_data_conversao']);
        $stmt->bindValue(':pessoa_data_batismo', $dados['pessoa_data_batismo']);
        $stmt->bindValue(':pessoa_batizada_local', $dados['pessoa_batizada_local']);
        $stmt->bindValue(':pessoa_cargo', $dados['pessoa_cargo']);
        $stmt->bindValue(':pessoa_situacao', $dados['pessoa_situacao']);
        $stmt->bindValue(':pessoa_informacoes', $dados['pessoa_informacoes']);

        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE pessoas SET
            pessoa_nome = :pessoa_nome, pessoa_cpf = :pessoa_cpf, pessoa_email = :pessoa_email,
            pessoa_aniversario = :pessoa_aniversario, pessoa_foto = :pessoa_foto,
            pessoa_familia = :pessoa_familia, pessoa_endereco = :pessoa_endereco,
            pessoa_municipio = :pessoa_municipio, pessoa_estado = :pessoa_estado,
             pessoa_telefone_celular = :pessoa_telefone_celular,
            pessoa_telefone_fixo = :pessoa_telefone_fixo, pessoa_instagram = :pessoa_instagram,
            pessoa_facebook = :pessoa_facebook, pessoa_data_conversao = :pessoa_data_conversao,
            pessoa_data_batismo = :pessoa_data_batismo, pessoa_batizada_local = :pessoa_batizada_local,
            pessoa_cargo = :pessoa_cargo, pessoa_situacao = :pessoa_situacao, pessoa_informacoes = :pessoa_informacoes
        WHERE pessoa_id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':pessoa_nome', $dados['pessoa_nome']);
        $stmt->bindValue(':pessoa_cpf', $dados['pessoa_cpf']);
        $stmt->bindValue(':pessoa_email', $dados['pessoa_email']);
        $stmt->bindValue(':pessoa_aniversario', $dados['pessoa_aniversario']);
        $stmt->bindValue(':pessoa_foto', $dados['pessoa_foto']);
        $stmt->bindValue(':pessoa_familia', $dados['pessoa_familia']);
        $stmt->bindValue(':pessoa_endereco', $dados['pessoa_endereco']);
        $stmt->bindValue(':pessoa_municipio', $dados['pessoa_municipio']);
        $stmt->bindValue(':pessoa_estado', $dados['pessoa_estado']);
        $stmt->bindValue(':pessoa_telefone_celular', $dados['pessoa_telefone_celular']);
        $stmt->bindValue(':pessoa_telefone_fixo', $dados['pessoa_telefone_fixo']);
        $stmt->bindValue(':pessoa_instagram', $dados['pessoa_instagram']);
        $stmt->bindValue(':pessoa_facebook', $dados['pessoa_facebook']);
        $stmt->bindValue(':pessoa_data_conversao', $dados['pessoa_data_conversao']);
        $stmt->bindValue(':pessoa_data_batismo', $dados['pessoa_data_batismo']);
        $stmt->bindValue(':pessoa_batizada_local', $dados['pessoa_batizada_local']);
        $stmt->bindValue(':pessoa_cargo', $dados['pessoa_cargo']);
        $stmt->bindValue(':pessoa_situacao', $dados['pessoa_situacao']);
        $stmt->bindValue(':pessoa_informacoes', $dados['pessoa_informacoes']);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function listar() {
        $query = "SELECT * FROM pessoas ORDER BY pessoa_nome ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id) {
        $query = "SELECT * FROM pessoas WHERE pessoa_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function apagar($id) {
        $query = "DELETE FROM pessoas WHERE pessoa_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
