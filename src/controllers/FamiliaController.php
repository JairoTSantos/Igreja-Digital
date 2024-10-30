<?php

require_once dirname(__DIR__) . '/models/FamiliaModel.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class FamiliaController {

    private $familiaModel;
    private $logger;

    public function __construct() {
        $this->familiaModel = new FamiliaModel();
        $this->logger = new Logger();
    }

    public function criar($dados) {
        if (empty($dados['familia_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Por favor, preencha o campo obrigatório: nome da família.'];
        }

        try {
            $result = $this->familiaModel->criar($dados);
            return ['status' => 'success', 'message' => 'Família cadastrada com sucesso.'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Essa família já foi cadastrada.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {
        if (empty($dados['familia_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Por favor, preencha o campo obrigatório: nome da família.'];
        }

        try {
            $result = $this->familiaModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Família atualizada com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Família não encontrada ou sem alterações nos dados.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Essa família já foi cadastrada.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function listar() {
        try {
            $result = $this->familiaModel->listar();

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Nenhuma família encontrada.'];
            }

            return ['status' => 'success', 'message' => count($result) . ' família(s) encontrada(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function buscar($id) {
        try {
            $result = $this->familiaModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Família não encontrada.'];
            }

            return ['status' => 'success', 'message' => 'Família encontrada.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {
        try {
            $result = $this->familiaModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Família excluída com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Família não encontrada.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Essa família não pode ser removida.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }
}
