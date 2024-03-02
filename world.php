<?php
// Precio dolar blue
$response_dolar_blue = file_get_contents('https://dolarapi.com/v1/dolares/blue');
if ($response_dolar_blue !== false) {
    $data_dolar_blue = json_decode($response_dolar_blue, true);
    $precio_compra = $data_dolar_blue['compra'];
} else {
    $precio_compra = 1;
}

// Precio world coin + calculos
$response_world_coin = file_get_contents('https://api.coinmarketcap.com/data-api/v3/cryptocurrency/detail?slug=worldcoin-org');
if ($response_world_coin !== false) {
    $data_world_coin = json_decode($response_world_coin, true);
    $precio = $data_world_coin['data']['statistics']['price'];
    $porcentaje_ultima_hora = $data_world_coin['data']['statistics']['priceChangePercentage1h'];
    $porcentaje_ultimas_24_horas = $data_world_coin['data']['statistics']['priceChangePercentage24h'];
    $porcentaje_ultimos_7_dias = $data_world_coin['data']['statistics']['priceChangePercentage7d'];
    
    $precio_truncado = intval($precio);

    $usd_comision_camila = ($precio_truncado * 13) * 0.05;
    $pesos_comision_camila = (($precio_truncado * 13) * 0.05) * $precio_compra;

    // Sin comision
    $usd_del_cliente = ($precio_truncado * 13) * 0.65;
    $pesos_del_cliente = (($precio_truncado * 13) * 0.65) * $precio_compra;

    $usd_restantes = ($precio_truncado * 13) * 0.30;
    $comision_cueva = round($usd_restantes * 0.05, 2);
    $ganancia_usd = $usd_restantes - $comision_cueva;
    $ganancia_pesos = $ganancia_usd * $precio_compra;

    $result = [
        'Precio WorldCoin' => $precio,
        'Precio Dolar Blue' => $precio_compra,
        'Cambios' => [
            'Porcentaje última hora' => round($porcentaje_ultima_hora, 2),
            'Porcentaje últimas 24 horas' => round($porcentaje_ultimas_24_horas, 2),
            'Porcentaje últimos 7 días' => round($porcentaje_ultimos_7_dias, 2),
        ],
        'Sin afiliación' => [
            'Cliente' => [
                'USD' => $usd_del_cliente,
                'PESOS' => $pesos_del_cliente,
            ],
            'Camila' => [
                'Comisión USD' => $usd_comision_camila,
                'Comisión PESOS' => $pesos_comision_camila,
            ],
            'Ganancia' => [
                'Comisión cueva' => $comision_cueva,
                'Ganancia USD' => $ganancia_usd,
                'Ganancia PESOS' => $ganancia_pesos,
            ],
        ],
    ];

    echo '<pre>' . json_encode($result, JSON_PRETTY_PRINT) . '</pre>';
}
?>
