<?php
require_once __DIR__ . '/../backend/config/database.php';

$db = getDB();

$nome  = 'Admin Teste';
$email = 'admin@teste.com';
$senha = '123456';
$plano = 'pro';

try {
    $stmt = $db->prepare('INSERT INTO usuarios (nome, email, senha_hash, plano) VALUES (?, ?, ?, ?)');
    $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $plano]);
    $usuarioId = $db->lastInsertId();

    $stmt = $db->prepare('INSERT INTO oficinas (usuario_id, nome) VALUES (?, ?)');
    $stmt->execute([$usuarioId, 'Oficina Teste']);

    echo "<pre>Usuário criado!\nE-mail: {$email}\nSenha: {$senha}\nPlano: {$plano}</pre>";
} catch (PDOException $e) {
    echo $e->getCode() == 23000
        ? "<pre>Usuário {$email} já existe.</pre>"
        : "<pre>Erro: " . htmlspecialchars($e->getMessage()) . "</pre>";
}
