<?php
function buscarEnderecoPorCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
    
    if (strlen($cep) !== 8) {
        return ['erro' => 'CEP inválido'];
    }
    
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        return ['erro' => 'Erro na comunicação com ViaCEP'];
    }
    
    $endereco = json_decode($response, true);
    
    return $endereco;
}
?>