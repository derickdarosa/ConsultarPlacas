<?php
session_start();
require_once '../../backend/config/bootstrap.php';

$erro    = $_SESSION['erro_login']    ?? '';
$sucesso = $_SESSION['sucesso_login'] ?? '';
unset($_SESSION['erro_login'], $_SESSION['sucesso_login']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Consultor de Placas</title>
    <script>
        (function () {
            var t = localStorage.getItem('consultor-tema') ||
                    (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', t);
        }());
    </script>
    <link rel="stylesheet" href="../css/login.css?v=<?= time() ?>">
</head>
<body>
    <button id="toggle-tema" title="Modo escuro"></button>

    <div id="container-external">
        <form action="../../backend/controllers/AuthController.php" method="POST">
            <div id="head-title">
                <h1>Consultor de Placas</h1>
                <p>Entre na sua conta para continuar</p>
            </div>

            <?php if ($sucesso): ?>
                <div class="sucesso-login"><?= htmlspecialchars($sucesso) ?></div>
            <?php endif; ?>
            <?php if ($erro): ?>
                <div class="erro-login"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <div id="boxEmail">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="Escreva seu e-mail" required>
            </div>
            <div id="boxSenha">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="Escreva sua senha" required>
            </div>
            <button type="submit" id="botaoEnviar">Entrar</button>
        </form>
        <p class="link-secundario">Não tem conta? <a href="../cadastro.php">Cadastre-se</a></p>
    </div>

    <script src="../js/tema.js"></script>
</body>
</html>
