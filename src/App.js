import React, { useState } from 'react';
import Login from './Login';
import ListGames from './ListGames';

const getAllMyGames = () => {
  let allMyGamesJSON = '';
  try {
    allMyGamesJSON = JSON.parse(localStorage.getItem('canardvapeur-games'));
  } catch (e) {
    console.error('localStorage data corrupted or empty');
  }
  return (allMyGamesJSON === null || allMyGamesJSON.ok === null) ? '' : allMyGamesJSON;
};

export default function App() {
  const [allMyGames, setAllMyGames] = useState(getAllMyGames());
  const [steamID, setSteamID] = useState(undefined);

  return (
    <>
      {(allMyGames === '')
        ? <Login setAllMyGames={setAllMyGames} setSteamID={setSteamID} steamID={steamID} />
        : <ListGames allMyGames={allMyGames} setAllMyGames={setAllMyGames} />}
    </>
  );
}
