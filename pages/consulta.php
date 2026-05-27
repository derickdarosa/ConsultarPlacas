<?php
session_start();
require_once '../backend/config/bootstrap.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login/login.php');
    exit;
}

require_once '../backend/services/PlacaService.php';
require_once '../backend/config/planos.php';
require_once '../backend/models/Usuario.php';

$resultado       = '';
$classeResultado = '';
$dados           = null;

if (isset($_GET['placa']) && $_GET['placa'] !== '') {
    $placa = strtoupper(trim($_GET['placa']));
    try {
        $res = consultarPlaca($placa, $_SESSION['oficina_id'], $_SESSION['plano']);
        if ($res['erro']) {
            $resultado       = $res['mensagem'];
            $classeResultado = 'erro';
        } else {
            $dados = $res['dados'];
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        $resultado       = 'Erro ao consultar a placa. Tente novamente.';
        $classeResultado = 'erro';
    }
}

try {
    $consultasMes = contarConsultasMes($_SESSION['oficina_id']);
} catch (Exception $e) {
    error_log($e->getMessage());
    $consultasMes = 0;
}

$limite      = LIMITES_PLANO[$_SESSION['plano']] ?? LIMITES_PLANO['free'];
$limiteTexto = $limite === PHP_INT_MAX ? 'Ilimitado' : $limite;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Placa</title>
    <script>
        (function () {
            var t = localStorage.getItem('consultor-tema') ||
                    (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', t);
        }());
    </script>
    <link rel="stylesheet" href="css/consulta.css?v=<?= time() ?>">
</head>
<body>
    <header id="topo">
        <span class="brand">Consultor de Placas</span>
        <div class="user-info">
            <span class="user-name">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
            <span class="badge"><?= htmlspecialchars($_SESSION['plano']) ?></span>
            <span class="quota"><?= $consultasMes ?>/<?= $limiteTexto ?> consultas</span>
            <button id="toggle-tema" title="Modo escuro"></button>
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <div id="layout">
        <aside id="sidebar">
            <a href="consulta.php" class="sidebar-link ativo">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Consultar
            </a>
            <a href="historico.php" class="sidebar-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 0 .5-4"/><polyline points="3 3 3 7 7 7"/></svg>
                Histórico
            </a>
        </aside>

        <div id="conteudo">
            <form action="consulta.php" method="get">
                <fieldset id="field-placa">
                    <legend>Consultor de Placas</legend>
                    <label for="placa">Consulte detalhes pela placa:</label>
                    <input
                        type="text"
                        name="placa"
                        id="placa"
                        required
                        maxlength="7"
                        placeholder="AAA0000 ou AAA0Z00"
                        pattern="[A-Za-z]{3}[0-9][A-Za-z0-9][0-9]{2}"
                        title="Placas modelo antigo ou Mercosul"
                    >
                    <button type="submit" id="botaoEnviar">Consultar Agora!</button>
                </fieldset>
            </form>

            <?php if ($resultado !== ''): ?>
                <div class="resultado <?= $classeResultado ?>">
                    <?= htmlspecialchars($resultado) ?>
                </div>
            <?php endif; ?>

            <?php if ($dados): ?>
                <div class="resultado sucesso">
                    <h2>Veículo Encontrado</h2>
                    <table class="tabela-veiculo">
                        <tr><th>Placa</th>            <td><?= htmlspecialchars($dados['placa']) ?></td></tr>
                        <tr><th>Marca</th>            <td><?= htmlspecialchars($dados['marca']) ?></td></tr>
                        <tr><th>Modelo</th>           <td><?= htmlspecialchars($dados['modelo']) ?></td></tr>
                        <tr><th>Ano</th>              <td><?= htmlspecialchars($dados['ano']) ?></td></tr>
                        <tr><th>Combustível</th>      <td><?= htmlspecialchars($dados['combustivel']) ?></td></tr>
                        <tr><th>Óleo recomendado</th> <td><?= htmlspecialchars($dados['oleo_recomendado']) ?></td></tr>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/tema.js"></script>
</body>
</html>
