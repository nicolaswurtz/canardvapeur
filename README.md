# Canard Vapeur
_Your Steam games library enhanced with reviews and ratings from CanardPC, the best French video game magazine !_

![Games library](capture1.jpg?raw=true "Games library")

![Game's detail](capture2.jpg?raw=true "Games library")

## API
PHP quick & dirty, some scripts to merge source of rating from
- Mad LL http://madll.free.fr/canardpc/
- Codinjutsu.org https://coincoinpc.herokuapp.com/

The main purpose is to collect Steam AppID for each games rated by CanardPC magazine.

## Front
React project that calls `mygames.php` with STEAM_ID to get games, and sync them with those found in the previous step.

Feel free to contribute, a lot of things are missing (translation, better API, lots of games undetected, interface to enter missing games, etc.).

It's a little side project, so don't expect I'll be reactive, forgive-me, but who knows... !

Enjoy ! Wabon ? DTC !
