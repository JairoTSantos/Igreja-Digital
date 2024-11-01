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

    public function listar($termo, $colunaOrdenacao, $ordem, $situacao, $pagina = 1, $limite = 10) {
        $colunasPermitidas = ['pessoa_nome', 'pessoa_id', 'pessoa_adicionado_em'];
        $ordensPermitidas = ['ASC', 'DESC'];
    
        // Validar coluna e ordem
        $colunaOrdenacao = in_array($colunaOrdenacao, $colunasPermitidas) ? $colunaOrdenacao : 'pessoa_nome';
        $ordem = in_array($ordem, $ordensPermitidas) ? $ordem : 'ASC';
    
        // Consulta base
        $baseQuery = "SELECT * FROM view_pessoas";
        $conditions = [];
    
        // Adicionar condições de filtro
        if (!empty($termo)) {
            $conditions[] = "pessoa_nome LIKE :termo";
        }
        if (!empty($situacao)) {
            $conditions[] = "pessoa_situacao = :situacao";
        }
    
        // Montar condições na query base
        if (!empty($conditions)) {
            $baseQuery .= " WHERE " . implode(" AND ", $conditions);
        }
    
        // Consulta para contar o total de registros
        $countQuery = str_replace("*", "COUNT(*) AS total", $baseQuery);
        $stmtCount = $this->db->prepare($countQuery);
    
        // Bind dos parâmetros para contagem
        if (!empty($termo)) {
            $stmtCount->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
        }
        if (!empty($situacao)) {
            $stmtCount->bindValue(':situacao', $situacao, PDO::PARAM_STR);
        }
    
        $stmtCount->execute();
        $totalRegistros = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Calcular total de páginas
        $totalPaginas = ceil($totalRegistros / $limite);
    
        // Definir offset para a paginação
        $offset = ($pagina - 1) * $limite;
        $query = $baseQuery . " ORDER BY {$colunaOrdenacao} {$ordem} LIMIT :limite OFFSET :offset";
    
        // Preparar e executar a query de listagem
        $stmt = $this->db->prepare($query);
    
        if (!empty($termo)) {
            $stmt->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
        }
        if (!empty($situacao)) {
            $stmt->bindValue(':situacao', $situacao, PDO::PARAM_STR);
        }
        
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Retornar resultados e dados de paginação
        return [
            'resultados' => $resultados,
            'pagina_atual' => $pagina,
            'total_paginas' => $totalPaginas,
            'total_registros' => $totalRegistros
        ];
    }
    
    public function buscar($id) {
        $query = "SELECT * FROM view_pessoas WHERE pessoa_id = :id";
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
