CREATE DATABASE IF NOT EXISTS consultor_placas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE consultor_placas;

CREATE TABLE IF NOT EXISTS usuarios (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    plano      ENUM('free', 'basico', 'pro') NOT NULL DEFAULT 'free',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS oficinas (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome       VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS consultas (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    oficina_id     INT NOT NULL,
    placa          VARCHAR(7) NOT NULL,
    resultado_json TEXT NOT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (oficina_id) REFERENCES oficinas(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cache_placas (
    placa        VARCHAR(7) PRIMARY KEY,
    dados_json   TEXT NOT NULL,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
