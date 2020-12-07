import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import Disclaimer from './components/Disclaimer';
import { CANARDVAPEUR_API } from './components/consts';

export default function Login(props) {
  const { setAllMyGames, steamID, setSteamID } = props;
  const [hasSubmitted, setHasSubmitted] = useState(false);
  const [error, setError] = useState(undefined);

  const handleChange = (e) => {
    setSteamID(e.target.value);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setHasSubmitted(true);
  };

  useEffect(() => {
    if (hasSubmitted && steamID !== undefined) {
      const fetchData = async () => {
        fetch(`${CANARDVAPEUR_API}?STEAM_ID=${steamID}`)
          .then((response) => response.json())
          .then((result) => {
            if (result.status_message === 'ok' && result.data.ok !== null) {
              localStorage.setItem('canardvapeur-games', JSON.stringify(result.data));
              setAllMyGames(result.data);
            } else if (result.data !== null && result.data.ok === null) {
              setError('Votre profil ne permet pas de consulter vos jeux (mode privé ?)');
            } else {
              setError('Une erreur terrible est survenue.');
            }
          });
      };
      fetchData();
    }
    setHasSubmitted(false);
  }, [steamID, hasSubmitted]);

  return (
    <>
      <div className="login">
        <div className="login-header">
          <div className="canardvapeur-logo" />
          <div>
            <span className="color-note10">C</span>
            <span className="color-note9">a</span>
            <span className="color-note8">n</span>
            <span className="color-note7">a</span>
            <span className="color-note6">r</span>
            <span className="color-note5">d</span>
            <small>Vapeur</small>
            <div className="normal-text">
              ©2020 Nicolas Wurtz
              <a href="https://twitter.com/NicolasW_GRAOU">
                <span className="login-logo twitter" />
              </a>
              <a href="https://github.com/nicolaswurtz/canardvapeur">
                <span className="login-logo github" />
              </a>
            </div>
          </div>
          <form onSubmit={handleSubmit} className="login-form">
            <input
              id="login"
              type="text"
              value={steamID}
              onChange={handleChange}
              placeholder="Steam ID 64 (dec)"
            />
            <button type="submit">OK</button>
          </form>
          <div className="error">
            {error !== undefined ? error : null}
          </div>
          <div className="login-help">
            Vous pouvez retrouver votre
            <strong> Steam ID 64 Dec </strong>
            (sous la forme 76591197974395885 par exemple) grâce à
            <a href="https://steamidfinder.com"> ce site.</a>
            <br />
            Votre profil
            <strong> ne doit pas </strong>
            être privé.
          </div>
        </div>
        <Disclaimer />
      </div>
    </>
  );
}

Login.propTypes = {
  setAllMyGames: PropTypes.func.isRequired,
  setSteamID: PropTypes.func.isRequired,
  steamID: PropTypes.string,
};
Login.defaultProps = {
  steamID: undefined,
};
