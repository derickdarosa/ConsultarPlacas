<?php
session_start();
$mensagem = $_SESSION['erro_sistema'] ?? 'Ocorreu um erro inesperado.';
unset($_SESSION['erro_sistema']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro — Consultor de Placas</title>
    <style>
        *, ::before, ::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: system-ui, sans-serif;
            background: #f5f5f7;
            display: grid;
            place-items: center;
            min-height: 100vh;
            color: #111827;
        }
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 48px 40px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        .icone { font-size: 2rem; margin-bottom: 16px; }
        h1 { font-size: 1.1rem; margin-bottom: 8px; }
        p { color: #6b7280; font-size: 0.88rem; line-height: 1.5; }
        a {
            display: inline-block;
            margin-top: 24px;
            color: #0f0f0f;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
        }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icone">⚠</div>
        <h1>Algo deu errado</h1>
        <p><?= htmlspecialchars($mensagem) ?></p>
        <a href="javascript:history.back()">&#8592; Voltar</a>
    </div>
</body>
</html>
