CREATE TABLE pessoas (
    -- DADOS PESSOAIS
    pessoa_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Identificador único para cada pessoa (chave primária).
    pessoa_nome VARCHAR(255) NOT NULL, -- Nome completo da pessoa.
    pessoa_cpf VARCHAR(14) NOT NULL UNIQUE, -- CPF da pessoa, com 11 dígitos + formatação (ex: 123.456.789-09), deve ser único.
    pessoa_email VARCHAR(255) NOT NULL, -- E-mail atualizado da pessoa.
    pessoa_aniversario DATE NOT NULL, -- Data de nascimento da pessoa (formato YYYY-MM-DD).
    pessoa_foto VARCHAR(255) DEFAULT NULL, -- Caminho ou URL da foto do usuário.
    pessoa_familia INT DEFAULT NULL, -- Referência à família da pessoa.
    pessoa_informacoes TEXT DEFAULT NULL,
    
    -- DADOS DE ENDEREÇO
    pessoa_endereco VARCHAR(255) DEFAULT NULL, -- Endereço de residência da pessoa.
    pessoa_municipio VARCHAR(50) NOT NULL, -- Município de residência da pessoa.
    pessoa_estado CHAR(2) NOT NULL, -- Estado de residência da pessoa (exemplo: SP, RJ)

    -- DADOS DE CONTATO
    pessoa_telefone_celular VARCHAR(20) DEFAULT NULL, -- Número de celular atual da pessoa.
    pessoa_telefone_fixo VARCHAR(20) DEFAULT NULL, -- Número de telefone fixo, se houver.
    pessoa_instagram VARCHAR(255) DEFAULT NULL, -- Nome de usuário do Instagram.
    pessoa_facebook VARCHAR(255) DEFAULT NULL, -- Nome de usuário do Facebook.

    -- DADOS ECLESIÁSTICOS
    pessoa_data_conversao DATE DEFAULT NULL, -- Data em que a pessoa se converteu.
    pessoa_data_batismo DATE DEFAULT NULL, -- Data de batismo da pessoa, se aplicável.
    pessoa_batizada_local TINYINT(1) NOT NULL, -- Indica se foi batizada na PIB (1), outro lugar (0) ou na foi batizado (3).

    -- DADOS ADMINISTRATIVOS
    pessoa_cargo INT NOT NULL, -- Referência ao cargo da pessoa (se aplicável).
    pessoa_situacao INT NOT NULL, -- Referência à situação dessa pessoa.

    -- TIMESTAMPS
    pessoa_adicionada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Data e hora em que o registro foi inserido.
    pessoa_atualizada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Data e hora da última atualização do registro.

) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE niveis_usuarios (
    nivel_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Identificador único para cada nível de usuário (chave primária).
    nivel_nome VARCHAR(255) NOT NULL UNIQUE, -- Nome do nível de usuário.
    nivel_descricao TEXT NOT NULL, -- descrição do nível de usuário.
    nivel_adicionado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Data e hora em que o registro foi inserido.
    nivel_atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Data e hora da última atualização do registro.
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO niveis_usuarios (nivel_nome, nivel_descricao) VALUES
    ('Administrativo','Acesso completo'),
    ('Financeiro', 'Acesso ao módulo financeiro'),
    ('Secretaria', 'Acesso ao módulo da secrataria'),
    ('Pastoral', 'Acesso ao módulo pastoral');

CREATE TABLE usuarios (
    pessoa_id INT NOT NULL PRIMARY KEY UNIQUE, -- Usa o ID da pessoa como chave primária.
    nivel_id INT NOT NULL, -- Referência à tabela 'niveis_usuarios' para indicar o nível de acesso do usuário.
    usuario_senha VARCHAR(255) NOT NULL, -- Senha do usuário (deve ser armazenada de forma segura, usando hash).
    usuario_ativo TINYINT(1) NOT NULL DEFAULT 1 -- Indica se o usuário está ativo (1) ou inativo (0).
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE cargos (
    cargo_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Identificador único para cada cargo (chave primária).
    cargo_nome VARCHAR(255) NOT NULL UNIQUE, -- Nome do cargo.
    cargo_descricao TEXT DEFAULT NULL, -- Descrição do cargo (opcional).
    cargo_adicionado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Data e hora em que o registro foi inserido.
    cargo_atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Data e hora da última atualização do registro.
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO cargos (cargo_nome, cargo_descricao) VALUES
    ('Sem cargo definido', 'Pessoa sem nenhum cargo na igreja.'),
    ('Pastor', 'Responsável pela liderança espiritual da igreja e pregação da Palavra.'),
    ('Diácono', 'Auxilia na administração da igreja e apoio ao pastor.'),
    ('Líder de Louvor', 'Responsável pela condução do louvor e adoração nos cultos.'),
    ('Professores de Escola Dominical', 'Responsáveis pelo ensino da Bíblia para crianças e adultos.'),
    ('Missionário', 'Atua em missões e evangelização, levando a mensagem do evangelho.'),
    ('Secretário', 'Responsável pela administração e organização dos documentos da igreja.'),
    ('Tesoureiro', 'Gerencia as finanças da igreja, incluindo arrecadação e doações.'),
    ('Líder de Jovens', 'Coordena atividades e ministérios voltados para a juventude.'),
    ('Coordenador de Eventos', 'Planeja e organiza eventos e atividades da igreja.');

CREATE TABLE pessoas_situacoes (
    situacao_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Identificador único para cada situação (chave primária).
    situacao_nome VARCHAR(255) NOT NULL UNIQUE, -- Nome da situação.
    situacao_descricao TEXT DEFAULT NULL, -- Descrição da situação (opcional).
    situacao_adicionada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Data e hora em que o registro foi inserido.
    situacao_atualizada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Data e hora da última atualização do registro.
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO pessoas_situacoes (situacao_nome, situacao_descricao) VALUES
    ('Sem situação definida', 'Pessoa sem uma situação definida.'),
    ('Membro', 'Pessoa que é membro ativo da igreja, participando regularmente das atividades e cultos.'),
    ('Não Membro', 'Pessoa que frequenta a igreja, mas não é membro oficial.'),
    ('Visitante', 'Pessoa que visita a igreja esporadicamente, sem compromisso formal.'),
    ('Falecido', 'Pessoa que já foi membro ou frequentador da igreja e faleceu.'),
    ('Desvinculado', 'Pessoa que foi membro da igreja, mas não está mais participando ativamente.'),
    ('Provisório', 'Pessoa que está em processo de membresia, aguardando confirmação.'),
    ('Frequente', 'Pessoa que participa regularmente dos cultos, mas não é membro oficial.'),
    ('Interino', 'Pessoa que está temporariamente em uma função ou cargo na igreja.');

CREATE TABLE familia (
    familia_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Identificador único para cada família (chave primária).
    familia_nome VARCHAR(255) NOT NULL UNIQUE, -- Nome da família (opcional, pode ser o sobrenome ou uma designação).
    familia_adicionada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Data e hora em que o registro foi inserido.
    familia_atualizada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Data e hora da última atualização do registro.
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO familia (familia_nome) VALUES ('Sem famílida definida');


-- Adicionando chave estrangeira na tabela 'usuarios' para relacionar com 'pessoas'
ALTER TABLE usuarios
ADD CONSTRAINT fk_usuarios_pessoa
FOREIGN KEY (pessoa_id) REFERENCES pessoas(pessoa_id) ON DELETE RESTRICT ON UPDATE NO ACTION; 

-- Adicionando chave estrangeira na tabela 'usuarios' para relacionar com 'niveis_usuarios'
ALTER TABLE usuarios
ADD CONSTRAINT fk_usuarios_nivel
FOREIGN KEY (nivel_id) REFERENCES niveis_usuarios(nivel_id) ON DELETE RESTRICT ON UPDATE NO ACTION; 

-- Adicionando chave estrangeira na tabela 'pessoas' para relacionar com 'pessoas_situacoes'
ALTER TABLE pessoas
ADD CONSTRAINT fk_pessoas_situacao
FOREIGN KEY (pessoa_situacao) REFERENCES pessoas_situacoes(situacao_id) ON DELETE RESTRICT ON UPDATE NO ACTION; 

-- Adicionando chave estrangeira na tabela 'pessoas' para relacionar com 'cargos'
ALTER TABLE pessoas
ADD CONSTRAINT fk_pessoas_cargo
FOREIGN KEY (pessoa_cargo) REFERENCES cargos(cargo_id) ON DELETE RESTRICT ON UPDATE NO ACTION; 

-- Adicionando chave estrangeira na tabela 'pessoas' para relacionar com 'familia'
ALTER TABLE pessoas
ADD CONSTRAINT fk_pessoas_familia
FOREIGN KEY (pessoa_familia) REFERENCES familia(familia_id) ON DELETE SET NULL ON UPDATE NO ACTION;