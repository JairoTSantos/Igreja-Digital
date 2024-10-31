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

        if (empty($dados['cargo_nome']) || strlen($dados['cargo_nome']) > 100) {
            return ['status' => 'bad_request', 'message' => 'Nome do cargo inválido.'];
        }
        if (empty($dados['cargo_descricao']) || strlen($dados['cargo_descricao']) > 255) {
            return ['status' => 'bad_request', 'message' => 'Descrição do cargo inválida.'];
        }

        $dados['cargo_nome'] = $this->sanitize($dados['cargo_nome']);
        $dados['cargo_descricao'] = $this->sanitize($dados['cargo_descricao']);

        try {
            $result = $this->cargoModel->criar($dados);
            return ['status' => 'success', 'message' => 'Cargo criado com sucesso.'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Esse cargo já existe.'];
            }
            $this->logger->novoLog('cargo_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {

        if (empty($dados['cargo_nome']) || strlen($dados['cargo_nome']) > 100) {
            return ['status' => 'bad_request', 'message' => 'Nome do cargo inválido.'];
        }
        if (empty($dados['cargo_descricao']) || strlen($dados['cargo_descricao']) > 255) {
            return ['status' => 'bad_request', 'message' => 'Descrição do cargo inválida.'];
        }

        $dados['cargo_nome'] = $this->sanitize($dados['cargo_nome']);
        $dados['cargo_descricao'] = $this->sanitize($dados['cargo_descricao']);

        try {
            $result = $this->cargoModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Cargo atualizado com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Cargo não encontrado ou sem alterações.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('cargo_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function listar($termo = '', $colunaOrdenacao = 'cargo_nome', $ordem = 'ASC') {
        try {
            $result = $this->cargoModel->listar($termo, $colunaOrdenacao, $ordem);
    
            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Nenhum cargo registrado.'];
            }
    
            return ['status' => 'success', 'message' => count($result) . ' cargos(os) encontrado(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('cargo_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }
    
    public function buscar($id) {

        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->cargoModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Cargo não encontrado.'];
            }

            return ['status' => 'success', 'message' => 'Cargo encontrado.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('cargo_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {

        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->cargoModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Cargo excluído com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Cargo não encontrado.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Esse cargo não pode ser removido.'];
            }
            $this->logger->novoLog('cargo_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}
