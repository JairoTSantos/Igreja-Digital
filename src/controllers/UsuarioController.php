<?php

require_once dirname(__DIR__) . '/models/UsuarioModel.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class UsuarioController {

    private $usuarioModel;
    private $logger;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        $this->logger = new Logger();
    }

    public function criar($dados) {
        if (empty($dados['pessoa_id']) || empty($dados['nivel_id']) || empty($dados['usuario_senha'])) {
            return ['status' => 'bad_request', 'message' => 'Os campos pessoa_id, nivel_id e usuario_senha são obrigatórios.'];
        }

        try {
            $result = $this->usuarioModel->criar($dados);
            return ['status' => 'success', 'message' => 'Usuário criado com sucesso.'];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {
        if (empty($dados['nivel_id']) || empty($dados['usuario_senha'])) {
            return ['status' => 'bad_request', 'message' => 'Os campos nivel_id e usuario_senha são obrigatórios.'];
        }

        try {
            $result = $this->usuarioModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Usuário atualizado com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Usuário não encontrado ou sem alterações.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function listar() {
        try {
            $result = $this->usuarioModel->listar();

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Nenhum usuário encontrado.'];
            }

            return ['status' => 'success', 'message' => count($result) . ' usuário(s) encontrado(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function buscar($id) {
        try {
            $result = $this->usuarioModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Usuário não encontrado.'];
            }

            return ['status' => 'success', 'message' => 'Usuário encontrado.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {
        try {
            $result = $this->usuarioModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Usuário excluído com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Usuário não encontrado.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Esse usuário não pode ser removido.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }
}
