<?php
require_once __DIR__ . '/../config/database.php';

function salvarConsulta(int $oficinaId, string $placa, array $dados): void {
    $stmt = getDB()->prepare('INSERT INTO consultas (oficina_id, placa, resultado_json) VALUES (?, ?, ?)');
    $stmt->execute([$oficinaId, $placa, json_encode($dados, JSON_UNESCAPED_UNICODE)]);
}

function buscarCachePlaca(string $placa): ?array {
    $stmt = getDB()->prepare('SELECT dados_json, atualizado_em FROM cache_placas WHERE placa = ?');
    $stmt->execute([$placa]);
    $row = $stmt->fetch();
    if (!$row) return null;

    $dias = (new DateTime())->diff(new DateTime($row['atualizado_em']))->days;
    if ($dias > 30) return null;

    return json_decode($row['dados_json'], true);
}

function salvarCachePlaca(string $placa, array $dados): void {
    $stmt = getDB()->prepare('
        INSERT INTO cache_placas (placa, dados_json) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE dados_json = VALUES(dados_json), atualizado_em = NOW()
    ');
    $stmt->execute([$placa, json_encode($dados, JSON_UNESCAPED_UNICODE)]);
}

function listarConsultas(int $oficinaId, int $limite = 50): array {
    $stmt = getDB()->prepare('
        SELECT placa, resultado_json, created_at
        FROM consultas
        WHERE oficina_id = ?
        ORDER BY created_at DESC
        LIMIT ?
    ');
    $stmt->execute([$oficinaId, $limite]);
    return $stmt->fetchAll();
}
