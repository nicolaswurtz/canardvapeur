<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../tools.php';
require __DIR__ . '/consts.php';
require __DIR__ . '/parseCoincoin.php';
require __DIR__ . '/parseSteam.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Colors\Color;
use PHPHtmlParser\Dom;

$html = new Dom;
$c = new Color();

$c->setTheme(
  array(
    'info' => array('white', 'bold'),
    'subinfo' => array('white'),
  )
);

echo $c('Beginning retrieving data...')->info->bold() . PHP_EOL;

// Convert name to standards
function convertNames($name)
{
  return html_entity_decode(str_replace(
    ['coeur', 'VIII', 'VII', 'VI', 'IV', 'V', 'III', 'II', 'I'],
    ['cœur', 8, 7, 6, 4, 5, 3, 2, 1],
    $name
  ), ENT_QUOTES);
}

// Download steam & madll games
function getGames($c)
{
  echo $c('Steam...')->subinfo . '...';
  $all_games_steam_url = "https://api.steampowered.com/ISteamApps/GetAppList/v0002/?key=STEAMKEY&format=json";
  $all_games_steam_json = json_decode(file_get_contents($all_games_steam_url));
  foreach ($all_games_steam_json->applist->apps as $app) {
    $all_games_steam[$app->appid] = $app->name;
  }
  file_put_contents('./all_games_steam.ser', serialize($all_games_steam));
  echo $c('OK')->subinfo . "\n";

  echo $c('MADLL...')->subinfo . '...';
  $all_games_cpc_url = "http://madll.free.fr/canardpc/zip/tests_canard-pc.xlsx";
  $all_games_cpc_xlsx = file_get_contents($all_games_cpc_url);
  file_put_contents('./all_games_cpc.xlsx', $all_games_cpc_xlsx);
  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
  $reader->setReadDataOnly(TRUE);
  $spreadsheet = $reader->load(__DIR__ . '/all_games_cpc.xlsx');
  $worksheet = $spreadsheet->getActiveSheet();

  foreach ($worksheet->getRowIterator() as $line_number => $row) {
    $columnNames = ['name', 'editor', 'genre', 'note', 'numcpc', 'year', 'testedBy', 'comment'];
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE);
    $i = 0;
    foreach ($cellIterator as $cell) {
      $line[$columnNames[$i]] = $cell->getValue();
      $i++;
    }
    $all_games_cpc[] = $line;
  }
  array_shift($all_games_cpc);
  file_put_contents('./all_games_cpc.ser', serialize($all_games_cpc));
  echo $c('OK')->subinfo . "\n";

  echo $c('COINCOIN...')->subinfo . '...';
  $titles = new Dom;
  $html = file_get_contents('https://coincoinpc.herokuapp.com/indexes/title.html');
  $titles->loadStr($html);

  $games = $titles->find('.list .item');

  foreach ($games as $game) {
    $list[] = array(
      'name' => trim($game->innerHTML),
      'link' => $game->getAttribute('href')
    );
  }
  file_put_contents('./all_games_coincoin.json', json_encode($list));
  echo $c('OK')->subinfo . "\n";
}

// getGames($c); exit;

//
// Get & format big array with steam games
//
$all_games_steam = unserialize(file_get_contents(__DIR__ . '/all_games_steam.ser'));
echo $c('  ' . count($all_games_steam) . ' STEAM games found')->subinfo . PHP_EOL;

//
// Retrieve & format MADLL Data / CPC games
//
$all_games_cpc = unserialize(file_get_contents(__DIR__ . '/all_games_cpc.ser'));
echo $c('  ' . count($all_games_cpc) . ' CPC games found')->subinfo . PHP_EOL;

//
// Parse COINCOIN
//
$all_game_coincoin = json_decode(file_get_contents(__DIR__ . '/all_games_coincoin.json'));
echo $c('  ' . count($all_game_coincoin) . ' COINCOIN games found')->subinfo . PHP_EOL;

// echo PHP_EOL . $c('Beginning merging games with COINCOIN data...')->info->bold() . PHP_EOL;
// $all_games_cpc = parseCoincoin($all_game_coincoin, $html, $all_games_cpc, $c);

//
// Parse STEAM
//
echo PHP_EOL . $c('Beginning merging games with STEAM data...')->info->bold() . PHP_EOL;
parseSteam($all_games_cpc, $all_games_steam, $c);

// Retrieve list of my games
// $url = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key='.$_SERVER['API_KEY'].'&steamid='.$_SERVER['STEAM_ID'].'&include_appinfo=true&format=json';
// $mesjeux = json_decode(file_get_contents('./steam.json'));
