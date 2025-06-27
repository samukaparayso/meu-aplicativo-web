<?php
// Recebe os dados do frontend
$data = json_decode(file_get_contents('php://input'), true);
$placa = isset($data['placa']) ? $data['placa'] : null;

if (!$placa) {
    echo json_encode(['success' => false, 'error' => 'Placa não fornecida']);
    exit;
}

// Monta a requisição para a API externa
$url = 'https://pagamento-segurohoje.store/veiculo/api.php';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['placa' => $placa]));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(['success' => false, 'error' => 'Erro ao consultar API externa: ' . $error]);
    exit;
}

header('Content-Type: application/json');
echo $response; 