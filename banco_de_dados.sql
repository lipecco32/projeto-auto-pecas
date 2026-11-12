-- DDL: Criação da estrutura do Banco de Dados
CREATE DATABASE IF NOT EXISTS auto_pecas_db;
USE auto_pecas_db;

-- Criação da tabela Produto
CREATE TABLE produto (
    id_produto INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    codigo_original VARCHAR(50) NOT NULL,
    fabricante VARCHAR(50) NOT NULL,
    veiculo_aplicacao VARCHAR(100) NOT NULL,
    localizacao VARCHAR(50) NOT NULL,
    quantidade_estoque INT NOT NULL,
    CONSTRAINT pk_produto PRIMARY KEY (id_produto)
);

-- Criação da tabela Movimentacao (Entrada/Saída)
CREATE TABLE movimentacao (
    id_movimentacao INT NOT NULL AUTO_INCREMENT,
    id_produto INT NOT NULL,
    tipo_movimentacao ENUM('E', 'S') NOT NULL, -- 'E' para Entrada, 'S' para Saída
    quantidade INT NOT NULL,
    data_movimentacao DATETIME NOT NULL,
    CONSTRAINT pk_movimentacao PRIMARY KEY (id_movimentacao),
    CONSTRAINT fk_movimentacao_produto FOREIGN KEY (id_produto) REFERENCES produto (id_produto)
);

-- DML: Inserção de dados iniciais para teste
INSERT INTO produto (nome, codigo_original, fabricante, veiculo_aplicacao, localizacao, quantidade_estoque) 
VALUES 
('Pastilha de Freio Dianteira', 'PF-1234', 'Cobreq', 'VW Gol G5 / Voyage', 'Corredor A - Prat 02', 15),
('Filtro de Óleo', 'FO-5678', 'Fram', 'Fiat Palio / Uno Fire', 'Corredor B - Prat 01', 30),
('Amortecedor Traseiro', 'AM-9101', 'Monroe', 'Chevrolet Onix', 'Corredor C - Prat 05', 8);

INSERT INTO movimentacao (id_produto, tipo_movimentacao, quantidade, data_movimentacao) 
VALUES 
(1, 'E', 20, '2026-11-01 08:30:00'),
(1, 'S', 5, '2026-11-05 14:15:00'),
(2, 'E', 30, '2026-11-02 09:00:00');

-- Parâmetros de conexão (Conforme solicitado na atividade)
/*
Host: localhost
Porta: 3306
Usuário: root
Senha: (vazia ou a senha configurada no seu ambiente local)
Banco de Dados: auto_pecas_db
*/