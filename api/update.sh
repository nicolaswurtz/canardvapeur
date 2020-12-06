#!/bin/sh
php imports/insertGameCoincoin.php $1 $2
php mygames.php && cp all_my_games.json ../canardvapeur/src/
