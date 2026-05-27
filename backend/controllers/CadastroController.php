<?php
session_start();
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../models/Usuario.php';

$nome      = trim($_POST['nome'] ?? '');
$email     = trim($_POST['email'] ?? '');
$nomeOfic  = trim($_POST['nome_oficina'] ?? '');
$senha     = $_POST['senha'] ?? '';
$confirmar = $_POST['confirmar_senha'] ?? '';

$erros = [];
if ($nome === '')                              $erros[] = 'Nome é obrigatório.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'E-mail inválido.';
if ($nomeOfic === '')                          $erros[] = 'Nome da oficina é obrigatório.';
if (strlen($senha) < 6)                        $erros[] = 'Senha deve ter pelo menos 6 caracteres.';
if ($senha !== $confirmar)                     $erros[] = 'As senhas não coincidem.';

if (!empty($erros)) {
    $_SESSION['erro_cadastro'] = implode(' ', $erros);
    header('Location: ../../pages/cadastro.php');
    exit;
}

try {
    if (emailExiste($email)) {
        $_SESSION['erro_cadastro'] = 'Este e-mail já está cadastrado.';
        header('Location: ../../pages/cadastro.php');
        exit;
    }

    criarUsuario($nome, $email, $senha, $nomeOfic);

    $_SESSION['sucesso_login'] = 'Conta criada! Faça login para continuar.';
    header('Location: ../../pages/login/login.php');
    exit;
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['erro_cadastro'] = 'Erro ao criar conta. Tente novamente.';
    header('Location: ../../pages/cadastro.php');
    exit;
}
