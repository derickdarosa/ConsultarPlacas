<?php
require_once __DIR__ . '/../config/planos.php';
require_once __DIR__ . '/../models/Consulta.php';
require_once __DIR__ . '/../models/Usuario.php';

function consultarPlaca(string $placa, int $oficinaId, string $plano): array {
    if (!preg_match('/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/', $placa)) {
        return ['erro' => true, 'mensagem' => 'Placa inválida!'];
    }

    $limite = LIMITES_PLANO[$plano] ?? LIMITES_PLANO['free'];
    $usadas = contarConsultasMes($oficinaId);

    if ($usadas >= $limite) {
        return ['erro' => true, 'mensagem' => "Limite de {$limite} consultas/mês do plano {$plano} atingido."];
    }

    $cache = buscarCachePlaca($placa);
    if ($cache) {
        salvarConsulta($oficinaId, $placa, $cache);
        return ['erro' => false, 'dados' => $cache];
    }

    // --- Mock: substituir pela chamada à API real ---
    $mocks = [
        'EKY7020' => [
            'marca'       => 'Volkswagen',
            'modelo'      => 'Gol 1.6 MSI',
            'ano'         => '2019',
            'combustivel' => 'Flex',
        ],
    ];

    $info = $mocks[$placa] ?? ['marca' => 'Fiat', 'modelo' => 'Uno 1.0', 'ano' => '2015', 'combustivel' => 'Flex'];

    $dados = ['placa' => $placa] + $info;
    // ------------------------------------------------

    $dados['oleo_recomendado'] = sugerirOleo($dados);

    salvarCachePlaca($placa, $dados);
    salvarConsulta($oficinaId, $placa, $dados);

    return ['erro' => false, 'dados' => $dados];
}

function sugerirOleo(array $veiculo): string {
    return ((int) ($veiculo['ano'] ?? 0)) >= 2017
        ? '5W-30 Sintético'
        : '10W-40 Semissintético';
}
