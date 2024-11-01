<?php

require_once dirname(__DIR__) . '/models/PessoaModel.php';
require_once dirname(__DIR__) . '/core/Logger.php';
require_once dirname(__DIR__) . '/core/UploadFile.php';

class PessoaController {
    private $pessoaModel;
    private $logger;
    private $uploadFile;
    private $pasta_foto;

    public function __construct() {
        $this->pessoaModel = new PessoaModel();
        $this->logger = new Logger();
        $this->pasta_foto = '/public/arquivos/fotos_pessoas/';
        $this->uploadFile = new UploadFile();
    }

    public function criar($dados) {

        if (empty($dados['pessoa_nome']) || strlen($dados['pessoa_nome']) > 255) {
            return ['status' => 'bad_request', 'message' => 'Nome da pessoa inválido.'];
        }
        if (empty($dados['pessoa_cpf']) || !preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $dados['pessoa_cpf'])) {
            return ['status' => 'bad_request', 'message' => 'CPF inválido.'];
        }
        if (empty($dados['pessoa_email']) || !filter_var($dados['pessoa_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'bad_request', 'message' => 'E-mail inválido.'];
        }

        if (empty($dados['pessoa_aniversario']) || !preg_match('/^\d{2}\/\d{2}$/', $dados['pessoa_aniversario'])) {
            return ['status' => 'bad_request', 'message' => 'Data de aniversário inválida.'];
        }

        list($dia, $mes) = explode('/', $dados['pessoa_aniversario']);
        if (!checkdate($mes, $dia, 2000)) {
            return ['status' => 'bad_request', 'message' => 'Data de aniversário inválida.'];
        }
        $dados['pessoa_aniversario'] = sprintf('2000-%02d-%02d', $mes, $dia);

        if (empty($dados['pessoa_municipio']) || strlen($dados['pessoa_municipio']) > 50) {
            return ['status' => 'bad_request', 'message' => 'Município inválido.'];
        }
        if (empty($dados['pessoa_estado']) || strlen($dados['pessoa_estado']) !== 2) {
            return ['status' => 'bad_request', 'message' => 'Estado inválido.'];
        }
        if (empty($dados['pessoa_batizada_local'])) {
            return ['status' => 'bad_request', 'message' => 'Informação sobre batismo é obrigatória.'];
        }
        if (empty($dados['pessoa_cargo'])) {
            return ['status' => 'bad_request', 'message' => 'Cargo é obrigatório.'];
        }
        if (empty($dados['pessoa_situacao'])) {
            return ['status' => 'bad_request', 'message' => 'Situação é obrigatória.'];
        }

        foreach (['pessoa_data_conversao', 'pessoa_data_batismo'] as $campo) {
            if (!empty($dados[$campo]) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dados[$campo])) {
                list($dia, $mes, $ano) = explode('/', $dados[$campo]);
                if (!checkdate($mes, $dia, $ano)) {
                    return ['status' => 'bad_request', 'message' => "Data de $campo inválida."];
                }
                $dados[$campo] = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
            } else {
                $dados[$campo] = null;
            }
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido', 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        }

        $dados['pessoa_nome'] = $this->sanitize($dados['pessoa_nome']);
        $dados['pessoa_cpf'] = $this->sanitize($dados['pessoa_cpf']);
        $dados['pessoa_email'] = $this->sanitize($dados['pessoa_email']);
        $dados['pessoa_aniversario'] = $this->sanitize($dados['pessoa_aniversario']);
        $dados['pessoa_foto'] = isset($dados['pessoa_foto']) ? $dados['pessoa_foto'] : null;
        $dados['pessoa_familia'] = isset($dados['pessoa_familia']) ? (int) $dados['pessoa_familia'] : null;
        $dados['pessoa_informacoes'] = isset($dados['pessoa_informacoes']) ? $this->sanitize($dados['pessoa_informacoes']) : null;
        $dados['pessoa_endereco'] = isset($dados['pessoa_endereco']) ? $this->sanitize($dados['pessoa_endereco']) : null;
        $dados['pessoa_municipio'] = $this->sanitize($dados['pessoa_municipio']);
        $dados['pessoa_estado'] = $this->sanitize($dados['pessoa_estado']);
        $dados['pessoa_telefone_celular'] = isset($dados['pessoa_telefone_celular']) ? $this->sanitize($dados['pessoa_telefone_celular']) : null;
        $dados['pessoa_telefone_fixo'] = isset($dados['pessoa_telefone_fixo']) ? $this->sanitize($dados['pessoa_telefone_fixo']) : null;
        $dados['pessoa_instagram'] = isset($dados['pessoa_instagram']) ? $this->sanitize($dados['pessoa_instagram']) : null;
        $dados['pessoa_facebook'] = isset($dados['pessoa_facebook']) ? $this->sanitize($dados['pessoa_facebook']) : null;
        $dados['pessoa_data_conversao'] = isset($dados['pessoa_data_conversao']) ? $this->sanitize($dados['pessoa_data_conversao']) : null;
        $dados['pessoa_data_batismo'] = isset($dados['pessoa_data_batismo']) ? $this->sanitize($dados['pessoa_data_batismo']) : null;
        $dados['pessoa_batizada_local'] = (int) $dados['pessoa_batizada_local'];
        $dados['pessoa_cargo'] = (int) $dados['pessoa_cargo'];
        $dados['pessoa_situacao'] = (int) $dados['pessoa_situacao'];

        try {
            $result = $this->pessoaModel->criar($dados);
            return ['status' => 'success', 'message' => 'Pessoa criada com sucesso.'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated', 'message' => 'Essa pessoa já existe.'];
            }
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {
        // Validações
        if (empty($dados['pessoa_nome']) || strlen($dados['pessoa_nome']) > 255) {
            return ['status' => 'bad_request', 'message' => 'Nome da pessoa inválido.'];
        }
        if (empty($dados['pessoa_cpf']) || !preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $dados['pessoa_cpf'])) {
            return ['status' => 'bad_request', 'message' => 'CPF inválido.'];
        }
        if (empty($dados['pessoa_email']) || !filter_var($dados['pessoa_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'bad_request', 'message' => 'E-mail inválido.'];
        }

        if (empty($dados['pessoa_aniversario']) || !preg_match('/^\d{2}\/\d{2}$/', $dados['pessoa_aniversario'])) {
            return ['status' => 'bad_request', 'message' => 'Data de aniversário inválida.'];
        }

        list($dia, $mes) = explode('/', $dados['pessoa_aniversario']);
        if (!checkdate($mes, $dia, 2000)) {
            return ['status' => 'bad_request', 'message' => 'Data de aniversário inválida.'];
        }
        $dados['pessoa_aniversario'] = sprintf('2000-%02d-%02d', $mes, $dia);

        if (empty($dados['pessoa_municipio']) || strlen($dados['pessoa_municipio']) > 50) {
            return ['status' => 'bad_request', 'message' => 'Município inválido.'];
        }
        if (empty($dados['pessoa_estado']) || strlen($dados['pessoa_estado']) !== 2) {
            return ['status' => 'bad_request', 'message' => 'Estado inválido.'];
        }
        if (empty($dados['pessoa_batizada_local'])) {
            return ['status' => 'bad_request', 'message' => 'Informação sobre batismo é obrigatória.'];
        }
        if (empty($dados['pessoa_cargo'])) {
            return ['status' => 'bad_request', 'message' => 'Cargo é obrigatório.'];
        }
        if (empty($dados['pessoa_situacao'])) {
            return ['status' => 'bad_request', 'message' => 'Situação é obrigatória.'];
        }

        foreach (['pessoa_data_conversao', 'pessoa_data_batismo'] as $campo) {
            if (!empty($dados[$campo]) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dados[$campo])) {
                list($dia, $mes, $ano) = explode('/', $dados[$campo]);
                if (!checkdate($mes, $dia, $ano)) {
                    return ['status' => 'bad_request', 'message' => "Data de $campo inválida."];
                }
                $dados[$campo] = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
            } else {
                $dados[$campo] = null;
            }
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido', 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        } else {
            $dados['pessoa_foto'] = null;
        }

        $dados['pessoa_nome'] = $this->sanitize($dados['pessoa_nome']);
        $dados['pessoa_cpf'] = $this->sanitize($dados['pessoa_cpf']);
        $dados['pessoa_email'] = $this->sanitize($dados['pessoa_email']);
        $dados['pessoa_aniversario'] = $this->sanitize($dados['pessoa_aniversario']);
        $dados['pessoa_foto'] = isset($dados['pessoa_foto']) ? $dados['pessoa_foto'] : '';
        $dados['pessoa_familia'] = isset($dados['pessoa_familia']) ? (int) $dados['pessoa_familia'] : null;
        $dados['pessoa_informacoes'] = isset($dados['pessoa_informacoes']) ? $this->sanitize($dados['pessoa_informacoes']) : null;
        $dados['pessoa_endereco'] = isset($dados['pessoa_endereco']) ? $this->sanitize($dados['pessoa_endereco']) : null;
        $dados['pessoa_municipio'] = $this->sanitize($dados['pessoa_municipio']);
        $dados['pessoa_estado'] = $this->sanitize($dados['pessoa_estado']);
        $dados['pessoa_telefone_celular'] = isset($dados['pessoa_telefone_celular']) ? $this->sanitize($dados['pessoa_telefone_celular']) : null;
        $dados['pessoa_telefone_fixo'] = isset($dados['pessoa_telefone_fixo']) ? $this->sanitize($dados['pessoa_telefone_fixo']) : null;
        $dados['pessoa_instagram'] = isset($dados['pessoa_instagram']) ? $this->sanitize($dados['pessoa_instagram']) : null;
        $dados['pessoa_facebook'] = isset($dados['pessoa_facebook']) ? $this->sanitize($dados['pessoa_facebook']) : null;
        $dados['pessoa_data_conversao'] = isset($dados['pessoa_data_conversao']) ? $this->sanitize($dados['pessoa_data_conversao']) : null;
        $dados['pessoa_data_batismo'] = isset($dados['pessoa_data_batismo']) ? $this->sanitize($dados['pessoa_data_batismo']) : null;
        $dados['pessoa_batizada_local'] = (int) $dados['pessoa_batizada_local']; // Assegura que seja um inteiro
        $dados['pessoa_cargo'] = (int) $dados['pessoa_cargo']; // Assegura que seja um inteiro
        $dados['pessoa_situacao'] = (int) $dados['pessoa_situacao']; // Assegura que seja um inteiro

        try {
            $result = $this->pessoaModel->atualizar($id, $dados);

            if ($result) {
                return ['status' => 'success', 'message' => 'Pessoa atualizada com sucesso.'];
            } else {
                return ['status' => 'not_found', 'message' => 'Pessoa não encontrada ou sem alterações.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function listar($termo = '', $colunaOrdenacao = 'pessoa_nome', $ordem = 'ASC', $situacao = null) {
        try {
            // Chama o método listar do modelo, passando o parâmetro $situacao
            $result = $this->pessoaModel->listar($termo, $colunaOrdenacao, $ordem, $situacao);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Nenhuma pessoa registrada.'];
            }

            return [
                'status' => 'success',
                'message' => count($result) . ' pessoa(s) encontrada(s).',
                'dados' => $result
            ];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Erro interno no servidor.',
                'error' => $e->getMessage()
            ];
        }
    }


    public function buscar($id) {
        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->pessoaModel->buscar($id);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Pessoa não encontrada.'];
            }

            return ['status' => 'success', 'message' => 'Pessoa encontrada.', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    public function apagar($id) {
        if (!is_numeric($id) || $id <= 0) {
            return ['status' => 'bad_request', 'message' => 'ID inválido.'];
        }

        try {
            $result = $this->buscar($id);
            $resultDelete = $this->pessoaModel->apagar($id);

            if ($resultDelete) {
                if ($result['dados']['pessoa_foto'] != null) {
                    unlink('..' . $result['dados']['pessoa_foto']);
                }
                return ['status' => 'success', 'message' => 'Pessoa excluída com sucesso.'];
            } else {
                return ['status' => 'error', 'message' => 'Pessoa não encontrada.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno no servidor.', 'error' => $e->getMessage()];
        }
    }

    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}
