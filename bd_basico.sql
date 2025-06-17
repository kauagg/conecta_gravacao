CREATE DATABASE conecta_festas;

USE conecta_festas;

CREATE TABLE Cliente (
    idCliente SMALLINT AUTO_INCREMENT PRIMARY KEY,
    email_cliente VARCHAR(100) NOT NULL UNIQUE,
    senha_cliente VARCHAR(255) NOT NULL,
    cpf_cliente CHAR(11) UNIQUE,
    nome_cliente VARCHAR(100),
    end_cliente VARCHAR(255),
    tel_cliente VARCHAR(15),
    data_nasc DATE
)ENGINE = InnoDB;

CREATE TABLE Evento (
idEvento INT AUTO_INCREMENT PRIMARY KEY,
nome_evento VARCHAR(100) NOT NULL,
data_evento DATETIME NOT NULL,
status_evento VARCHAR(15) NOT NULL,
descricao_evento VARCHAR(255) NOT NULL
)ENGINE = InnoDB;

-- insert para teste

INSERT INTO Cliente 
(email_cliente, senha_cliente, cpf_cliente, nome_cliente, end_cliente, tel_cliente, data_nasc)
VALUES 
('joana.silva@gmail.com', 'senha123hash', '12345678901', 'Joana da Silva', 'Rua das Flores, 123 - São Paulo, SP', '(11)91234-5678', '1990-05-20');
INSERT INTO Cliente 
(email_cliente, senha_cliente, cpf_cliente, nome_cliente, end_cliente, tel_cliente, data_nasc)
VALUES 
('carlos.souza@hotmail.com', 'senha456hash', '98765432100', 'Carlos Souza', 'Av. Brasil, 456 - Rio de Janeiro, RJ', '(21)99876-5432', '1985-10-15');
INSERT INTO Cliente 
(email_cliente, senha_cliente, cpf_cliente, nome_cliente, end_cliente, tel_cliente, data_nasc)
VALUES 
('ana.martins@yahoo.com', 'senha789hash', '32165498700', 'Ana Martins', 'Rua Central, 789 - Belo Horizonte, MG', '(31)97765-4321', '1995-08-30');