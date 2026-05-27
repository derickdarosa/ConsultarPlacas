<?php
require_once __DIR__ . '/../services/PlateService.php';

class PlateController {
    public function consultar(string $placa, int $oficinaId, string $plano): string {
        $service = new PlateService();
        $dados = $service->consultarPlaca($placa, $oficinaId, $plano);
        return json_encode($dados, JSON_UNESCAPED_UNICODE);
    }
}
