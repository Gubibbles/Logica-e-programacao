-- Banco de dados do MeuBairro
-- Execute este SQL no phpMyAdmin ou no MySQL Workbench antes de usar o sistema

CREATE DATABASE IF NOT EXISTS meubairro;
USE meubairro;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(14) UNIQUE,
    nome VARCHAR(100),
    email VARCHAR(100),
    celular VARCHAR(15),
    cep VARCHAR(9),
    bairro VARCHAR(50),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de pontos
CREATE TABLE pontos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    titulo VARCHAR(100),
    descricao TEXT,
    tipo ENUM('bazar', 'evento', 'servico', 'alerta'),
    endereco VARCHAR(200),
    data_evento DATE,
    hora_evento TIME,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de avisos por bairro
CREATE TABLE avisos_bairro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bairro VARCHAR(100) NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    mensagem TEXT NOT NULL,
    tipo ENUM('obra', 'alerta', 'informacao', 'evento') NOT NULL,
    data_publicacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_validade DATE NULL
);