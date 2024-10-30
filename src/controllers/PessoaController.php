<?php

require_once dirname(__DIR__) . '/models/PessoaModel.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class PessoaController {

    private $pessoaModel;
    private $logger;

    public function __construct() {
        $this->pessoaModel = new PessoaModel();
        $this->logger = new Logger();
    }

    public function criar($dados) {

        if (isset($dados['pessoa_aniversario']) && preg_match('/^\d{2}\/\d{2}$/', $dados['pessoa_aniversario'])) {
            [$dia, $mes] = explode('/', $dados['pessoa_aniversario']);
            $dados['pessoa_aniversario'] = "2000-$mes-$dia";
        }

        if (isset($dados['pessoa_data_conversao']) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dados['pessoa_data_conversao'])) {
            [$dia, $mes, $ano] = explode('/', $dados['pessoa_data_conversao']);
            $dados['pessoa_data_conversao'] = "$ano-$mes-$dia";
        }

        if (isset($dados['pessoa_data_batismo']) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dados['pessoa_data_batismo'])) {
            [$dia, $mes, $ano] = explode('/', $dados['pessoa_data_batismo']);
            $dados['pessoa_data_batismo'] = "$ano-$mes-$dia";
        }

        if (!filter_var($dados['pessoa_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'erro', 'message' => 'Parece que o e-mail fornecido não é válido.'];
        }

        try {
            $result = $this->pessoaModel->criar($dados);
            return ['status' => 'success', 'message' => 'Pessoa cadastrada com sucesso.'];
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'CPF já cadastrado.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {
        if (empty($dados['pessoa_nome']) || empty($dados['pessoa_cpf']) || empty($dados['pessoa_aniversario'])) {
            return ['status' => 'bad_request', 'message' => 'Por favor, preencha os campos obrigatórios.'];
        }

        try {
            $result = $this->pessoaModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Pessoa atualizada com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Pessoa não encontrada ou sem alterações nos dados.'];
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'CPF já cadastrado.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function listar() {
        try {
            $result = $this->pessoaModel->listar();

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Nenhuma pessoa encontrada.'];
            }

            return ['status' => 'success', 'message' => count($result) . ' pessoa(s) encontrada(s).', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function buscar($id) {
        try {
            $result = $this->pessoaModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'vazio', 'message' => 'Pessoa não encontrada.'];
            }

            return ['status' => 'success', 'message' => 'Pessoa encontrada.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {
        try {
            $result = $this->pessoaModel->apagar($id);

            if ($result) {
                return ['status' => 'success', 'message' => 'Pessoa excluída com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Pessoa não encontrada.'];
            }
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict', 'message' => 'Essa pessoa não pode ser removida.'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }
}
