<?php

function extractCoincoin($html, $all = false)
{
  $subtitletoremove = $html->find('main.pusher .sub.header')[0]->outerHTML;
  $name = trim(html_entity_decode(str_replace($subtitletoremove, '', $html->find('.ui.centered.huge.header .content')[0]->innerHTML), ENT_QUOTES));

  $subtitle = trim(html_entity_decode($html->find('main.pusher .sub.header')[0]->innerHTML, ENT_QUOTES));

  $avatar = $html->find('.item .ui.tiny.avatar.image')[0];

  $testedBy = trim(html_entity_decode(str_replace(['https://coincoinpc.herokuapp.com/reviewer/','/reviewer/','.html'], '', $avatar->href), ENT_QUOTES));

  $author = strtolower(str_replace(['https://coincoinpc.herokuapp.com/reviewer/','/reviewer/','.html','%20',' '], '', $avatar->href));
  if (!file_exists(__DIR__ . '/../pictures/cpc/' . $author . '.png')) {
    $img_url = 'https://coincoinpc.herokuapp.com' . $html->find('.item .ui.tiny.avatar.image img')[0]->getAttribute('src');
    file_put_contents(__DIR__ . '/../pictures/cpc/' . $author . '.png', file_get_contents($img_url));
  }

  $description = trim(html_entity_decode(str_replace(
    ['<i class="quote left icon"></i>','<i class="quote right icon"></i>'],
    '',
    $html->find('.item .content .description p')[0]->innerHTML
  ), ENT_QUOTES));

  $cpc_num = intval(str_replace(['/magazine/', '.html'], '', $html->find('.item a.ui.small.image')[0]->getAttribute('href')));
  if (!file_exists(__DIR__ . '/../pictures/cpc/' . $cpc_num . '.jpg')) {
    $img_url = $html->find('.item a.ui.small.image img')[0]->getAttribute('src');
    file_put_contents(__DIR__ . '/../pictures/cpc/' . $cpc_num . '.jpg', file_get_contents($img_url));
  }

  $note = trim(html_entity_decode($html->find('.ui.red.right.floated.horizontal.statistic .value')->innerHTML, ENT_QUOTES));
  if (!is_numeric($note)) {
    $comment = $note;
    $note = null;
  }
  else {
    $note = intval($note);
  }

  foreach ($html->find('.ui.centered.very.basic.collapsing.compact.unstackable.table tbody tr td a') as $i => $a) {
    switch ($i) {
      case 0:
        $editor = trim(html_entity_decode($a->innerHTML, ENT_QUOTES));
        break;
      case 1:
        $editor .= ' / '.trim(html_entity_decode($a->innerHTML, ENT_QUOTES));
        break;
      case 2:
        $genre = trim(html_entity_decode($a->innerHTML, ENT_QUOTES));
        break;
    }
  }

  $year = intval($html->find('.ui.centered.very.basic.collapsing.compact.unstackable.table tbody tr td')[9]->innerHTML);

  return ($all) ?
    array(
      'name' => $name,
      'note' => $note,
      'year' => $year,
      'editor' => $editor,
      'genre' => $genre,
      'testedBy' => $testedBy,
      'comment' => '',
      'subtitle' => $subtitle,
      'author_coincoin' => $author,
      'description' => $description,
      'numcpc' => $cpc_num,
      'numcpc_coincoin' => $cpc_num,
    ) : array(
      'subtitle' => $subtitle,
      'author_coincoin' => $author,
      'description' => $description,
      'numcpc_coincoin' => $cpc_num,
    );
}

function parseCoincoin($all_game_coincoin, $html, $all_games_cpc, $c)
{
  //
  // First pass in equality
  //
  foreach ($all_game_coincoin as $line_number_coincoin => $game_coincoin) {
    $game_name_coincoin = (isset(COINCOIN2CPC[convertNames($game_coincoin->name)])) ?
      strtolower(str_ireplace(FILTER,'',COINCOIN2CPC[convertNames($game_coincoin->name)]))
      : strtolower(str_ireplace(FILTER,'',convertNames($game_coincoin->name)));

    foreach ($all_games_cpc as $line_number_cpc => $game_cpc) {
      if ($game_name_coincoin == strtolower(str_ireplace(FILTER,'',convertNames($game_cpc['name'])))) {
        echo '  '.$c($game_cpc['name'])->green . PHP_EOL;
        if (!isset($all_games_cpc[$line_number_cpc]->description) and $html->loadFromFile('https://coincoinpc.herokuapp.com' . $game_coincoin->link)) {
          $all_games_cpc[$line_number_cpc] = array_merge($all_games_cpc[$line_number_cpc], extractCoincoin($html));
          file_put_contents('./all_games_cpc.ser', serialize($all_games_cpc));
        }
        $nb_founds++;
        unset($all_game_coincoin[$line_number_coincoin]);
        break;
      }
    }
  }

  //
  // Second pass in equality
  //
  foreach ($all_game_coincoin as $line_number_coincoin => $game_coincoin) {
    $found = false;

    $game_name_coincoin = strtolower(str_ireplace(FILTER,'',convertNames($game_coincoin->name)));

    foreach ($all_games_cpc as $line_number_cpc => $game_cpc) {
      similar_text($game_name_coincoin, strtolower(str_ireplace(FILTER,'',convertNames($game_cpc['name']))), $percent);
      if ($percent > 75) {
        echo '  '.$c($game_cpc['name'])->yellow . PHP_EOL;
        if (!isset($all_games_cpc[$line_number_cpc]->description) and $html->loadFromFile('https://coincoinpc.herokuapp.com' . $game_coincoin->link)) {
          $all_games_cpc[$line_number_cpc] = array_merge($all_games_cpc[$line_number_cpc], extractCoincoin($html));
          file_put_contents('./all_games_cpc.ser', serialize($all_games_cpc));
        }
        $nb_founds++;
        unset($all_games_cpc[$line_number_cpc]);
        $found = true;
        break;
      }
    }
    if (!$found) {
      echo '  '.$c(html_entity_decode($game_coincoin->name, ENT_QUOTES))->red . PHP_EOL;
    }
    $found = false;
  }
}
