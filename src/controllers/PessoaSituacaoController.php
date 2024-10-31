<?php

require_once dirname(__DIR__) . '/models/PessoaSituacaoModel.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class PessoaSituacaoController {
    private $pessoaSituacaoModel;
    private $logger;

    public function __construct() {
        $this->pessoaSituacaoModel = new PessoaSituacaoModel();
        $this->logger = new Logger();
    }

    public function criar($dados) {

        if (empty($dados['situacao_nome']) || strlen($dados['situacao_nome']) > 100) {
            return ['status' => 'bad_request', 'message' => 'Nome da situação inválido.'];
        }
        if (empty($dados['situacao_descricao']) || strlen($dados['situacao_descricao']) > 255) {
            return ['status' => 'bad_request', 'message' => 'Descrição da situação inválida.'];
        }

        $dados['situacao_nome'] = $this->sanitize($dados['situacao_nome']);
        $dados['situacao_descricao'] = $this->sanitize($dados['situacao_descricao']);

        try {
            $result = $this->pessoaSituacaoModel->criar($dados);
            return ['status' => 'success', 'message' => 'Situação criada com sucesso.'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Essa situação já existe.'];
            }
            $this->logger->novoLog('pessoa_situacao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {

        if (empty($dados['situacao_nome']) || strlen($dados['situacao_nome']) > 100) {
            return ['status' => 'bad_request', 'message' => 'Nome da situação inválido.'];
        }
        if (empty($dados['situacao_descricao']) || strlen($dados['situacao_descricao']) > 255) {
            return ['status' => 'bad_request', 'message' => 'Descrição da situação inválida.'];
        }

        $dados['situacao_nome'] = $this->sanitize($dados['situacao_nome']);
        $dados['situacao_descricao'] = $this->sanitize($dados['situacao_descricao']);

        try {
            $result = $this->pessoaSituacaoModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Situação atualizada com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Situação não encontrada ou sem alterações.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_situacao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function listar($termo = '', $colunaOrdenacao = 'situacao_nome', $ordem = 'ASC') {
        try {
            $result = $this->pessoaSituacaoModel->listar($termo, $colunaOrdenacao, $ordem);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Nenhuma situação registrada.'];
            }

            return ['status' => 'success', 'message' => count($result) . ' situação(ões) encontrada(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_situacao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function buscar($id) {

        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->pessoaSituacaoModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Situação não encontrada.'];
            }

            return ['status' => 'success', 'message' => 'Situação encontrada.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_situacao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {

        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->pessoaSituacaoModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Situação excluída com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Situação não encontrada.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Essa situação não pode ser removida.'];
            }
            $this->logger->novoLog('pessoa_situacao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}
