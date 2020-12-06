<?php

header("Content-Type:application/json");

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/tools.php';

if (!isset($_GET['STEAM_ID']) || empty($_GET['STEAM_ID'])) {
  response(200, 'nok', null);
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Steam/CPC games
$all_games_cpc = json_decode(file_get_contents(__DIR__ . '/imports/all_games_cpc_with_appid.json'));

// Retrieve list of my games
$url = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key='.$_SERVER['API_KEY'].'&steamid='.$_GET['STEAM_ID'].'&include_appinfo=true&format=json';
// file_put_contents('./mygames_steam.json', file_get_contents($url)); exit;
// $mygames = json_decode(file_get_contents('./mygames_steam.json'));
if ($result = file_get_contents($url)) {
  $mygames = json_decode($result);
  foreach ($mygames->response->games as $game) {
    $appid = $game->appid;
    if (isset($all_games_cpc->{$appid})) {
      $all_my_games['ok'][$appid] = $all_games_cpc->{$appid};
      $all_my_games['ok'][$appid]->time = $game->playtime_forever;
      $all_my_games['ok'][$appid]->humantime = min2h($game->playtime_forever);
      $all_my_games['ok'][$appid]->img_icon_url = $game->img_icon_url;
      $all_my_games['ok'][$appid]->img_logo_url = $game->img_logo_url;
    }
    else {
      $all_my_games['nok'][$appid] = $game;
      $all_my_games['nok'][$appid]->humantime = min2h($game->playtime_forever);
    }
  }

  // Retrieve my personal informations
  $url = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$_SERVER['API_KEY'].'&steamids='.$_GET['STEAM_ID'];
  $personal_informations = json_decode(file_get_contents($url));

  $data = array(
    'user' => $personal_informations->response->players[0],
    'ok' => array_values($all_my_games['ok']),
    'nok' => array_values($all_my_games['nok'])
  );
  response(200, 'ok', $data);
}

response(200, 'nok', null);
