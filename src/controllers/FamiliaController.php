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
        if (empty($dados['familia_nome']) || strlen($dados['familia_nome']) > 100) {
            return ['status' => 'bad_request', 'message' => 'Nome da família inválido.'];
        }

        $dados['familia_nome'] = $this->sanitize($dados['familia_nome']);

        try {
            $result = $this->familiaModel->criar($dados);
            return ['status' => 'success', 'message' => 'Família criada com sucesso.'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Essa família já existe.'];
            }
            $this->logger->novoLog('familia_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {
        if (empty($dados['familia_nome']) || strlen($dados['familia_nome']) > 100) {
            return ['status' => 'bad_request', 'message' => 'Nome da família inválido.'];
        }

        $dados['familia_nome'] = $this->sanitize($dados['familia_nome']);

        try {
            $result = $this->familiaModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Família atualizada com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Família não encontrada ou sem alterações.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Essa família já existe.'];
            }
            $this->logger->novoLog('familia_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function listar($termo = '', $colunaOrdenacao = 'familia_nome', $ordem = 'ASC') {
        try {
            $result = $this->familiaModel->listar($termo, $colunaOrdenacao, $ordem);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Nenhuma família registrada.'];
            }

            return ['status' => 'success', 'message' => count($result) . ' famílias encontrada(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('familia_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function buscar($id) {
        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->familiaModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Família não encontrada.'];
            }

            return ['status' => 'success', 'message' => 'Família encontrada.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('familia_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {
        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->familiaModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Família excluída com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Família não encontrada.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Esse cargo não pode ser removido.'];
            }
            $this->logger->novoLog('familia_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}
