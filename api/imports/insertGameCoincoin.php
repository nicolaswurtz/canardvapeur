<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/parseCoincoin.php';

use PHPHtmlParser\Dom;

$html = new Dom;

$appid = $argv[1];
$urlCoincoin = $argv[2];
if ($html->loadFromFile($urlCoincoin)) {

  $all_games_cpc_with_appid = json_decode(file_get_contents(__DIR__ . '/all_games_cpc_with_appid.json'));
  $all_games_cpc_with_appid->{$appid} = array_merge(['id' => $appid], extractCoincoin($html, true));
  file_put_contents(__DIR__ . '/all_games_cpc_with_appid.json', json_encode($all_games_cpc_with_appid, JSON_PRETTY_PRINT));

  print_r($all_games_cpc_with_appid->{$appid});
}
else {
  echo "ERROR\n";
}
