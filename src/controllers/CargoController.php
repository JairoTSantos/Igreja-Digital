<?php

require_once dirname(__DIR__) . '/models/CargoModel.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class CargoController {

    private $cargoModel;
    private $logger;

    public function __construct() {
        $this->cargoModel = new CargoModel();
        $this->logger = new Logger();
    }

    public function criar($dados) {
        if (empty($dados['cargo_nome']) || empty($dados['cargo_descricao'])) {
            return ['status' => 'bad_request', 'message' => 'Por favor, preencha todos os campos obrigatórios.'];
        }

        try {
            $result = $this->cargoModel->criar($dados);
            return ['status' => 'success', 'message' => 'Cargo cadastrado com sucesso.'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Esse cargo já foi cadastrado.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {
        if (empty($dados['cargo_nome']) || empty($dados['cargo_descricao'])) {
            return ['status' => 'bad_request', 'message' => 'Por favor, preencha todos os campos obrigatórios.'];
        }

        try {
            $result = $this->cargoModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Cargo atualizado com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Cargo não encontrado ou sem alterações nos dados.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Esse cargo já foi cadastrado.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function listar() {
        try {
            $result = $this->cargoModel->listar();

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Nenhum cargo encontrado.'];
            }

            return ['status' => 'success', 'message' => count($result) . ' cargo(s) encontrado(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function buscar($id) {
        try {
            $result = $this->cargoModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Cargo não encontrado.'];
            }

            return ['status' => 'success', 'message' => 'Cargo encontrado.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {
        try {
            $result = $this->cargoModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Cargo excluído com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Cargo não encontrado.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Esse cargo não pode ser removido. Existem pessoas que estão nesse cargo.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.', 'error' => $e->getMessage()];
        }
    }
}
