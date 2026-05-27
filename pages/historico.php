<?php
session_start();
require_once '../backend/config/bootstrap.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login/login.php');
    exit;
}

require_once '../backend/models/Consulta.php';
require_once '../backend/config/planos.php';
require_once '../backend/models/Usuario.php';

$erroCarregamento = '';
$consultas = [];

try {
    $consultas    = listarConsultas($_SESSION['oficina_id']);
    $consultasMes = contarConsultasMes($_SESSION['oficina_id']);
    $limite       = LIMITES_PLANO[$_SESSION['plano']] ?? LIMITES_PLANO['free'];
    $limiteTexto  = $limite === PHP_INT_MAX ? 'Ilimitado' : $limite;
} catch (Exception $e) {
    error_log($e->getMessage());
    $erroCarregamento = 'Não foi possível carregar o histórico. Tente novamente.';
    $consultasMes = 0;
    $limiteTexto  = '-';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico — Consultor de Placas</title>
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
            <a href="consulta.php" class="sidebar-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Consultar
            </a>
            <a href="historico.php" class="sidebar-link ativo">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 0 .5-4"/><polyline points="3 3 3 7 7 7"/></svg>
                Histórico
            </a>
        </aside>

        <div id="conteudo" class="topo">
            <?php if ($erroCarregamento): ?>
                <div class="resultado erro"><?= htmlspecialchars($erroCarregamento) ?></div>
            <?php else: ?>
                <div class="historico-card">
                    <div class="historico-header">
                        <h2>Histórico de Consultas</h2>
                        <span class="historico-total"><?= count($consultas) ?> registro(s)</span>
                    </div>

                    <?php if (empty($consultas)): ?>
                        <div class="historico-vazio">
                            <p>Nenhuma consulta realizada ainda.</p>
                            <a href="consulta.php">Fazer primeira consulta</a>
                        </div>
                    <?php else: ?>
                        <table class="tabela-historico">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Placa</th>
                                    <th>Veículo</th>
                                    <th>Ano</th>
                                    <th>Combustível</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consultas as $c):
                                    $d = json_decode($c['resultado_json'], true) ?? [];
                                ?>
                                <tr>
                                    <td class="data-col"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
                                    <td><span class="placa-tag"><?= htmlspecialchars($c['placa']) ?></span></td>
                                    <td><?= htmlspecialchars(($d['marca'] ?? '') . ' ' . ($d['modelo'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars($d['ano'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($d['combustivel'] ?? '-') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/tema.js"></script>
</body>
</html>
