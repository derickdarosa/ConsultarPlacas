<?php
session_start();
require_once '../backend/config/bootstrap.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: consulta.php');
    exit;
}

$erro = $_SESSION['erro_cadastro'] ?? '';
unset($_SESSION['erro_cadastro']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta — Consultor de Placas</title>
    <script>
        (function () {
            var t = localStorage.getItem('consultor-tema') ||
                    (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', t);
        }());
    </script>
    <link rel="stylesheet" href="css/login.css?v=<?= time() ?>">
</head>
<body>
    <button id="toggle-tema" title="Modo escuro"></button>

    <div id="container-external" class="cadastro">
        <form action="../backend/controllers/CadastroController.php" method="POST">
            <div id="head-title">
                <h1>Criar Conta</h1>
                <p>Preencha os dados para começar</p>
            </div>

            <?php if ($erro): ?>
                <div class="erro-login"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <div class="secao">
                <div class="campo">
                    <label for="nome">Nome completo</label>
                    <input type="text" name="nome" id="nome" placeholder="Seu nome completo" required autocomplete="name">
                </div>
                <div class="campo">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="seu@email.com" required autocomplete="email">
                </div>
            </div>

            <div class="secao-titulo">Sua oficina</div>

            <div class="secao">
                <div class="campo">
                    <label for="nome_oficina">Nome da oficina</label>
                    <input type="text" name="nome_oficina" id="nome_oficina" placeholder="Ex: Oficina do João" required>
                </div>
            </div>

            <div class="secao-titulo">Senha de acesso</div>

            <div class="secao">
                <div class="campo">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Mínimo 6 caracteres" required minlength="6">
                </div>
                <div class="campo">
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Repita a senha" required>
                </div>
            </div>

            <button type="submit" id="botaoEnviar">Criar Conta</button>

            <div class="plano-info">
                <span class="plano-badge">Grátis</span>
                <span>10 consultas/mês · Upgrade disponível a qualquer momento</span>
            </div>
        </form>

        <p class="link-secundario">Já tem conta? <a href="login/login.php">Faça login</a></p>
    </div>

    <script src="js/tema.js"></script>
</body>
</html>
