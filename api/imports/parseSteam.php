<?php

function parseSteam($all_games_cpc, $all_games_steam, $c)
{
  $all_games_cpc_done = (file_exists('./all_games_cpc_done.ser')) ?
    unserialize(file_get_contents('./all_games_cpc_done.ser'))
    : [];

  $all_games_cpc_with_appid = (file_exists('./all_games_cpc_with_appid.json')) ?
    json_decode(file_get_contents('./all_games_cpc_with_appid.json'))
    : [];

  // First pass with same name
  foreach ($all_games_cpc as $line_number => $game_cpc) {
    $game_name_cpc = strtolower(str_ireplace(FILTER,'',$game_cpc['name']));
    if (!isset($all_games_cpc_done[$game_name_cpc])) {
      foreach ($all_games_steam as $appid => $game_name_steam) {
        if ($game_name_cpc == strtolower(str_ireplace(FILTER,'',$game_name_steam))) {
          $nb_founds++;
          $all_games_cpc_with_appid->{$appid} = json_decode(json_encode($game_cpc));
          $all_games_cpc_with_appid->{$appid}->id = $appid;
          $all_games_cpc_with_appid->{$appid}->name = $game_name_steam;
          $all_games_cpc_with_appid->{$appid}->cpcName = $game_cpc['name'];

          echo '  ' . $c($game_name_steam)->green() . ' | ' . $game_cpc['name'] . '(' . $line_number . ')' . PHP_EOL;
          $all_games_cpc_done[$game_name_cpc] = true;
          file_put_contents('./all_games_cpc_done.ser', serialize($all_games_cpc_done));
          file_put_contents('./all_games_cpc_with_appid.ser', json_encode($all_games_cpc_with_appid));
          unset($all_games_steam[$appid]);
          unset($all_games_cpc[$line_number]);
          break;
        }
      }
    }
  }

  // Second pass with similar_text
  foreach ($all_games_cpc as $line_number => $game_cpc) {
    $game_name_cpc = strtolower(str_ireplace(FILTER,'',$game_cpc['name']));
    if (!isset($all_games_cpc_done[$game_name_cpc])) {
      $nb_founds++;
      $found = false;
      foreach ($all_games_steam as $appid => $game_name_steam) {
        similar_text($game_name_cpc, strtolower(str_ireplace(FILTER,'',$game_name_steam)), $percent);
        if ($percent > 75) {
          $found = true;
          $all_games_cpc_with_appid->{$appid} = json_decode(json_encode($game_cpc));
          $all_games_cpc_with_appid->{$appid}->id = $appid;
          $all_games_cpc_with_appid->{$appid}->name = $game_name_steam;
          $all_games_cpc_with_appid->{$appid}->cpcName = $game_cpc['name'];

          echo '  ' . $c($game_name_steam)->yellow() . ' | ' . $game_cpc['name'] . '(' . $line_number . ')' . PHP_EOL;
          file_put_contents('./all_games_cpc_done.ser', serialize($all_games_cpc_done));
          file_put_contents('./all_games_cpc_with_appid.ser', json_encode($all_games_cpc_with_appid));
          $all_games_cpc_done[$game_name_cpc] = true;
          unset($all_games_steam[$appid]);
          unset($all_games_cpc[$line_number]);
          break;
        }
      }
      if (!$found) {
        echo '  '.$c($game_cpc['name'])->red . PHP_EOL;
      }
      $found = false;
    }
  }

  echo $nb_founds.' trouvés, manquent '.count($all_games_cpc) - $nb_founds . ' jeux';
}
