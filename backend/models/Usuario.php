<?php
require_once __DIR__ . '/../config/database.php';

function buscarUsuarioPorEmail(string $email): ?array {
    $stmt = getDB()->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    return $stmt->fetch() ?: null;
}

function buscarOficinaPorUsuario(int $usuarioId): ?array {
    $stmt = getDB()->prepare('SELECT * FROM oficinas WHERE usuario_id = ? LIMIT 1');
    $stmt->execute([$usuarioId]);
    return $stmt->fetch() ?: null;
}

function contarConsultasMes(int $oficinaId): int {
    $stmt = getDB()->prepare('
        SELECT COUNT(*) AS total FROM consultas
        WHERE oficina_id = ?
          AND MONTH(created_at) = MONTH(NOW())
          AND YEAR(created_at)  = YEAR(NOW())
    ');
    $stmt->execute([$oficinaId]);
    return (int) $stmt->fetch()['total'];
}

function emailExiste(string $email): bool {
    $stmt = getDB()->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    return $stmt->fetch() !== false;
}

function criarUsuario(string $nome, string $email, string $senha, string $nomeOficina): void {
    $db = getDB();
    $db->beginTransaction();
    try {
        $stmt = $db->prepare('INSERT INTO usuarios (nome, email, senha_hash, plano) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), 'free']);
        $usuarioId = $db->lastInsertId();

        $stmt = $db->prepare('INSERT INTO oficinas (usuario_id, nome) VALUES (?, ?)');
        $stmt->execute([$usuarioId, $nomeOficina]);

        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}
