USE sporthub;

CREATE TABLE clubes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    foto_perfil VARCHAR(255),
    biografia TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ligas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liga VARCHAR(255) NOT NULL,
    competicao VARCHAR(255),
    categoria VARCHAR(255),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `cpf_cnpj` varchar(25) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `tipo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE ligas ADD COLUMN id_clube INT NOT NULL AFTER id;
ALTER TABLE ligas ADD FOREIGN KEY (id_clube) REFERENCES clubes(id) ON DELETE CASCADE;

ALTER TABLE ligas ADD COLUMN logo VARCHAR(255) NULL AFTER categoria;


CREATE TABLE peneiras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_clube INT NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    data DATETIME NOT NULL,
    local VARCHAR(255) NOT NULL,
    informacoes TEXT,
    link_inscricao VARCHAR(255),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_clube) REFERENCES clubes(id) ON DELETE CASCADE
);

ALTER TABLE clubes ADD COLUMN id_usuario INT UNIQUE;



ALTER TABLE clubes ADD COLUMN id_usuario INT UNIQUE;

ALTER TABLE clubes ADD COLUMN id_usuario INT NOT NULL AFTER id;
ALTER TABLE clubes ADD FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE;

SELECT * FROM clubes WHERE id_usuario = 1;

ALTER TABLE clubes ADD COLUMN id_usuario INT NOT NULL AFTER id;
ALTER TABLE clubes ADD FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE;

