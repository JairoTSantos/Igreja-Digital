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

        if (empty($dados['situacao_nome']) || empty($dados['situacao_descricao'])) {
            return ['status' => 'bad_request', 'message' => 'Por favor, preencha todos os campos obrigatórios.'];
        }

        try {
            $result = $this->pessoaSituacaoModel->criar($dados);
            return ['status' => 'success', 'message' => 'Situação cadastrada com sucesso.'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Essa situação já foi cadastrada.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {

        if (empty($dados['situacao_nome']) || empty($dados['situacao_descricao'])) {
            return ['status' => 'bad_request', 'message' => 'Por favor, preencha todos os campos obrigatórios.'];
        }

        try {
            $result = $this->pessoaSituacaoModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Situação atualizada com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Situação não encontrada ou sem alterações nos dados.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Essa situação já foi cadastrada.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function listar() {
        try {
            $result = $this->pessoaSituacaoModel->listar();

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Nenhuma situação encontrada.'];
            }

            return ['status' => 'success', 'message' => count($result) . ' situação(ões) encontrada(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function buscar($id) {
        try {
            $result = $this->pessoaSituacaoModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Situação não encontrada.'];
            }

            return ['status' => 'success', 'message' => 'Situação encontrada.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {
        try {
            $result = $this->pessoaSituacaoModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Situação excluída com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Situação não encontrada.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Essa situacão não pode ser removida.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }
}
