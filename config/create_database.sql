-- Script para criar o banco de dados academico
CREATE DATABASE IF NOT EXISTS academico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Selecionar o banco de dados
USE academico;

-- Tabela de perfis de usuário
CREATE TABLE IF NOT EXISTS perfis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil_id INT NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    token_reset VARCHAR(100) DEFAULT NULL,
    token_expira DATETIME DEFAULT NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (perfil_id) REFERENCES perfis(id)
);

-- Tabela de cursos
CREATE TABLE IF NOT EXISTS cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    carga_horaria INT NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    coordenador_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (coordenador_id) REFERENCES usuarios(id)
);

-- Tabela de disciplinas
CREATE TABLE IF NOT EXISTS disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    carga_horaria INT NOT NULL,
    curso_id INT NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
);

-- Tabela de turmas
CREATE TABLE IF NOT EXISTS turmas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    ano INT NOT NULL,
    semestre TINYINT NOT NULL,
    curso_id INT NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
);

-- Tabela de matrículas (alunos em turmas)
CREATE TABLE IF NOT EXISTS matriculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    turma_id INT NOT NULL,
    data_matricula DATE NOT NULL,
    status ENUM('ativa', 'trancada', 'cancelada', 'concluida') DEFAULT 'ativa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id),
    FOREIGN KEY (turma_id) REFERENCES turmas(id),
    UNIQUE KEY (aluno_id, turma_id)
);

-- Tabela de alocação de professores em disciplinas
CREATE TABLE IF NOT EXISTS professor_disciplina (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    turma_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (professor_id) REFERENCES usuarios(id),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    FOREIGN KEY (turma_id) REFERENCES turmas(id),
    UNIQUE KEY (professor_id, disciplina_id, turma_id)
);

-- Tabela de aulas
CREATE TABLE IF NOT EXISTS aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    data_aula DATE NOT NULL,
    conteudo TEXT,
    professor_disciplina_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (professor_disciplina_id) REFERENCES professor_disciplina(id)
);

-- Tabela de frequência
CREATE TABLE IF NOT EXISTS frequencia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    aula_id INT NOT NULL,
    presente TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id),
    FOREIGN KEY (aula_id) REFERENCES aulas(id),
    UNIQUE KEY (aluno_id, aula_id)
);

-- Tabela de avaliações
CREATE TABLE IF NOT EXISTS avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    tipo ENUM('prova', 'trabalho', 'projeto', 'outro') NOT NULL,
    data_aplicacao DATE NOT NULL,
    valor DECIMAL(5,2) NOT NULL,
    professor_disciplina_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (professor_disciplina_id) REFERENCES professor_disciplina(id)
);

-- Tabela de notas
CREATE TABLE IF NOT EXISTS notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    avaliacao_id INT NOT NULL,
    nota DECIMAL(5,2) NOT NULL,
    observacao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id),
    FOREIGN KEY (avaliacao_id) REFERENCES avaliacoes(id),
    UNIQUE KEY (aluno_id, avaliacao_id)
);

-- Tabela de materias de aula
CREATE TABLE IF NOT EXISTS materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    arquivo VARCHAR(255) NOT NULL,
    tipo_arquivo VARCHAR(50),
    aula_id INT,
    disciplina_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (aula_id) REFERENCES aulas(id),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id)
);

-- Tabela de mensagens
CREATE TABLE IF NOT EXISTS mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remetente_id INT NOT NULL,
    destinatario_id INT NOT NULL,
    assunto VARCHAR(100) NOT NULL,
    conteudo TEXT NOT NULL,
    lida TINYINT(1) DEFAULT 0,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (remetente_id) REFERENCES usuarios(id),
    FOREIGN KEY (destinatario_id) REFERENCES usuarios(id)
);

-- Tabela de configurações do sistema
CREATE TABLE IF NOT EXISTS configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(50) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir perfis básicos
INSERT INTO perfis (nome, descricao) VALUES
('admin', 'Administrador do sistema'),
('secretaria', 'Secretaria acadêmica'),
('coordenador', 'Coordenador de curso'),
('professor', 'Professor'),
('aluno', 'Estudante');

-- Inserir usuário administrador padrão (senha: admin123)
INSERT INTO usuarios (nome, email, senha, perfil_id) VALUES
('Administrador', 'admin@exemplo.com', '$2y$10$8tGmHPvWxjjzCWTCM9A/8.fRNULBgP0SSXFWOXnQC9Iz3QLGXvBhO', 1);

-- Inserir configurações iniciais
INSERT INTO configuracoes (chave, valor, descricao) VALUES
('nome_instituicao', 'Sistema Acadêmico', 'Nome da instituição de ensino'),
('endereco_instituicao', 'Rua Exemplo, 123', 'Endereço da instituição'),
('telefone_instituicao', '(11) 1234-5678', 'Telefone de contato da instituição'),
('email_instituicao', 'contato@exemplo.com', 'Email de contato da instituição'),
('ano_letivo_atual', '2023', 'Ano letivo atual'),
('semestre_atual', '2', 'Semestre atual');