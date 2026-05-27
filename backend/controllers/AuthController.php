<?php
session_start();

require_once __DIR__ . '/../../backend/models/Usuario.php';

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if ($email === '' || $senha === '') {
    $_SESSION['erro_login'] = 'Preencha todos os campos.';
    header('Location: ../../pages/login/login.php');
    exit;
}

$usuario = buscarUsuarioPorEmail($email);

if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
    $_SESSION['erro_login'] = 'E-mail ou senha inválidos.';
    header('Location: ../../pages/login/login.php');
    exit;
}

$oficina = buscarOficinaPorUsuario($usuario['id']);

if (!$oficina) {
    $_SESSION['erro_login'] = 'Nenhuma oficina associada a este usuário.';
    header('Location: ../../pages/login/login.php');
    exit;
}

$_SESSION['usuario_id']   = $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['oficina_id']   = $oficina['id'];
$_SESSION['plano']        = $usuario['plano'];

header('Location: ../../pages/consulta.php');
exit;
