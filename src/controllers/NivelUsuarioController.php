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

        if (empty($dados['nivel_nome']) || strlen($dados['nivel_nome']) > 100) {
            return ['status' => 'bad_request', 'message' => 'Nome do nível inválido.'];
        }
        if (empty($dados['nivel_descricao']) || strlen($dados['nivel_descricao']) > 255) {
            return ['status' => 'bad_request', 'message' => 'Descrição do nível inválida.'];
        }

        $dados['nivel_nome'] = $this->sanitize($dados['nivel_nome']);
        $dados['nivel_descricao'] = $this->sanitize($dados['nivel_descricao']);

        try {
            $result = $this->nivelUsuarioModel->criar($dados);
            return ['status' => 'success', 'message' => 'Nível de usuário criado com sucesso.'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Esse nível de usuário já existe.'];
            }
            $this->logger->novoLog('nivel_usuario_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {

        if (empty($dados['nivel_nome']) || strlen($dados['nivel_nome']) > 100) {
            return ['status' => 'bad_request', 'message' => 'Nome do nível inválido.'];
        }
        if (empty($dados['nivel_descricao']) || strlen($dados['nivel_descricao']) > 255) {
            return ['status' => 'bad_request', 'message' => 'Descrição do nível inválida.'];
        }

        $dados['nivel_nome'] = $this->sanitize($dados['nivel_nome']);
        $dados['nivel_descricao'] = $this->sanitize($dados['nivel_descricao']);

        try {
            $result = $this->nivelUsuarioModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Nível de usuário atualizado com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Nível de usuário não encontrado ou sem alterações.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('nivel_usuario_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function listar($termo = '', $colunaOrdenacao = 'nivel_nome', $ordem = 'ASC') {
        try {
            $result = $this->nivelUsuarioModel->listar($termo, $colunaOrdenacao, $ordem);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Nenhum nível de usuário registrado.'];
            }

            return ['status' => 'success', 'message' => count($result) . ' nível(eis) de usuário encontrado(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('nivel_usuario_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function buscar($id) {

        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->nivelUsuarioModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Nível de usuário não encontrado.'];
            }

            return ['status' => 'success', 'message' => 'Nível de usuário encontrado.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('nivel_usuario_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {

        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->nivelUsuarioModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Nível de usuário excluído com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Nível de usuário não encontrado.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Esse nível de usuário não pode ser removido.'];
            }
            $this->logger->novoLog('nivel_usuario_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}
