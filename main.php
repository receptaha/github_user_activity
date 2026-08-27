<?php

$username = $argv[1];
$page = (int) ($argv[2] ?? 1);
$perPage = (int) ($argv[3] ?? 10);
$userAgent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:153.0) Gecko/20100101 Firefox/153.0";

$url = "https://api.github.com/users/{$username}/events?page={$page}&per_page={$perPage}";

try{
    $ch = curl_init();
    if($ch === false) {
        throw new \Exception("Curl init error!");
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/vnd.github+json"]);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

    $response = curl_exec($ch);
    if($response === false) {
        throw new \Exception("Curl exec error!");
    }
    curl_close($ch);

    $events = json_decode($response);
    if(empty($events))
        return;

    print_r($events);
}catch(\Exception $e)
{
    echo $e->getMessage();
}