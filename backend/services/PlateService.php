<?php
require_once __DIR__ . '/../config/planos.php';
require_once __DIR__ . '/../models/Consulta.php';
require_once __DIR__ . '/../models/Usuario.php';

class PlateService {
    public function consultarPlaca(string $placa, int $oficinaId, string $plano): array {
        if (!preg_match('/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/', $placa)) {
            return ['erro' => true, 'mensagem' => 'Placa inválida!'];
        }

        $limite = LIMITES_PLANO[$plano] ?? LIMITES_PLANO['free'];
        $usadas = contarConsultasMes($oficinaId);

        if ($usadas >= $limite) {
            return ['erro' => true, 'mensagem' => "Limite de {$limite} consultas/mês atingido."];
        }

        $cache = buscarCachePlaca($placa);
        if ($cache) {
            salvarConsulta($oficinaId, $placa, $cache);
            return ['erro' => false, 'dados' => $cache];
        }

        // --- Mock: substituir pela chamada à API real ---
        $response = [
            'placa'       => $placa,
            'marca'       => 'Fiat',
            'modelo'      => 'Uno',
            'ano'         => '2015',
            'combustivel' => 'Flex',
        ];
        // ------------------------------------------------

        $response['oleo_recomendado'] = $this->sugerirOleo($response);

        salvarCachePlaca($placa, $response);
        salvarConsulta($oficinaId, $placa, $response);

        return ['erro' => false, 'dados' => $response];
    }

    private function sugerirOleo(array $veiculo): string {
        return '5W-30 Sintético';
    }
}
