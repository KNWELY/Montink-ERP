<?php
function consultarViaCEP(string $cep): ?array {
    $cep = preg_replace('/\D/', '', $cep);
    if (strlen($cep) !== 8) {
        return null;
    }
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response === false) {
        return null;
    }
    $data = json_decode($response, true);
    if (isset($data['erro'])) {
        return null;
    }
    return $data;
}
