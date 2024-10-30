<?php

require_once dirname(__DIR__) . '/models/NivelUsuarioModel.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class NivelUsuarioController {

    private $nivelUsuarioModel;
    private $logger;

    public function __construct() {
        $this->nivelUsuarioModel = new NivelUsuarioModel();
        $this->logger = new Logger();
    }

    public function criar($dados) {
        if (empty($dados['nivel_nome']) || empty($dados['nivel_descricao'])) {
            return ['status' => 'bad_request', 'message' => 'O nome e a descrição do nível são obrigatórios.'];
        }

        try {
            $result = $this->nivelUsuarioModel->criar($dados);
            return ['status' => 'success', 'message' => 'Nível de usuário criado com sucesso.'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Esse nível de usuário já foi cadastrado.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {
        if (empty($dados['nivel_nome']) || empty($dados['nivel_descricao'])) {
            return ['status' => 'bad_request', 'message' => 'O nome do nível é obrigatório.'];
        }

        try {
            $result = $this->nivelUsuarioModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Nível de usuário atualizado com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Nível de usuário não encontrado ou sem alterações.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Esse nível de usuário já foi cadastrado.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function listar() {
        try {
            $result = $this->nivelUsuarioModel->listar();

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Nenhum nível de usuário encontrado.'];
            }

            return ['status' => 'success', 'message' => count($result) . ' nível(is) encontrado(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function buscar($id) {
        try {
            $result = $this->nivelUsuarioModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Nível de usuário não encontrado.'];
            }

            return ['status' => 'success', 'message' => 'Nível de usuário encontrado.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {
        try {
            $result = $this->nivelUsuarioModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Nível de usuário excluído com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Nível de usuário não encontrado.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Esse nível não pode ser removido.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }
}
