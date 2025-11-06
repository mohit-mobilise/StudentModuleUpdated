<?php
function getCurlData($endpoint, $params = []) {
    $baseApiUrl = "https://schoolerpbeta.mobilisepro.com/";
    $apiUrl = $baseApiUrl . $endpoint;
    if (!empty($params)) {
        $apiUrl .= '?' . http_build_query($params);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        return ["error" => curl_error($ch)];
    }
    curl_close($ch);
    $data = json_decode($response, true);
    return $data;
}
?>
