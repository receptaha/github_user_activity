<?php

require_once "functions.php";
$usage = "Usage: php {$argv[0]} <username> [access_token]";

$username = $argv[1] ?? null;
if(!$username) {
    echo $usage;
    exit(-1);
}

$ch = null;
$accessToken = $argv[2] ?? null;
$url = "https://api.github.com/users/{$username}/events";
$userAgent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:153.0) Gecko/20100101 Firefox/153.0";

$headers = [
    "Accept: application/vnd.github+json",
];
if($accessToken) {
    $headers[] = "Authorization: Bearer {$accessToken}";
}

try{
    $ch = curl_init();
    if($ch === false) {
        throw new \Exception("Curl init error!");
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

    $response = curl_exec($ch);
    if($response === false) {
        throw new \Exception("Curl exec error!");
    }
    $response = json_decode($response, true);
    if(empty($response)) {
        throw new \Exception("{$username} events are empty!");
    }

    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($statusCode !== 200) {
        $errorMessage = $response['message'] ?? $response['error'] ?? 'Unknown API error';
        throw new \Exception("GitHub API Error [{$statusCode}]: {$errorMessage}");
    }

    if(isset($response['created_at'])) {
        usort($response, fn($a, $b) => $b['created_at'] <=> $a['created_at']);
    }
    printEvents($response);

}catch(\Exception $e)
{
    echo $e->getMessage();
}finally {
    if(isset($ch)) {
        curl_close($ch);
    }
}