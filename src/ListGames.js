import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import nextId from 'react-id-generator';
import { FaSearch } from 'react-icons/fa';
import { MdExitToApp } from 'react-icons/md';
import { searchFilter, note2color } from './helpers';
import Header from './components/Header';
import GameDetails from './components/GameDetails';
import WaffleChart from './components/WaffleChart';
import Switch from './components/Switch';
import { STEAM_IMG_URL } from './components/consts';

const ListGames = (props) => {
  const { allMyGames, setAllMyGames } = props;
  const [filter, setFilter] = useState('');
  const [gameVisible, setGameVisible] = useState(0);
  const [averageNote, setAverageNote] = useState(0);
  const [switches, setSwitches] = useState({
    neverPlayed: false,
    sortByNote: false,
  });
  const [list, setList] = useState(allMyGames.ok);

  useEffect(() => {
    setGameVisible(0);
    searchFilter(
      filter,
      allMyGames.ok,
      setList,
      setAverageNote,
      switches,
    );
  }, [filter, switches]);

  const onInputChange = (event) => {
    setFilter(event.target.value);
  };

  const returnToLogin = () => {
    localStorage.setItem('canardvapeur-games', null);
    setAllMyGames('');
  };

  const toggleGameVisible = (id) => {
    if (id === gameVisible) {
      setGameVisible(0);
    } else {
      setGameVisible(id);
    }
  };

  const toggleSwitches = (name) => {
    setSwitches({ ...switches, [name]: !switches[name] });
  };

  return (
    <>
      <Header infos={{ version: '1.0.0' }} />
      <div className="listgames-header">
        <div className="searchfield">
          <FaSearch />
          <input
            type="text"
            onChange={onInputChange}
            className="searchfield-input"
            value={filter}
            placeholder="Nom, testeur, studio..."
          />
        </div>
        <div className="info-and-parameters">
          <div className="info-games">
            <div className="firstline">
              {allMyGames.ok.length}
              <small>JEUX</small>
            </div>
            <div className="secondline">
              {list.length}
              <small>FILTRÉS</small>
            </div>
          </div>
          <div className={`average-note note${Math.round(averageNote)}`}>
            {averageNote}
          </div>
          <div className="switches">
            <Switch
              id="neverPlayed"
              label="Jamais joués"
              isChecked={switches.neverPlayed}
              toggleChecked={() => toggleSwitches('neverPlayed')}
            />
            <Switch
              id="sortByNote"
              label="Tri par note"
              isChecked={switches.sortByNote}
              toggleChecked={() => toggleSwitches('sortByNote')}
            />
          </div>
        </div>
        <div className="infos-user">
          <div>
            <div className="infos-user-nickname">
              {allMyGames.user.personaname}
            </div>
            <div className="infos-user-realname">
              {allMyGames.user.realname}
            </div>
          </div>
          <div className="infos-user-avatar">
            <img src={allMyGames.user.avatarmedium} alt="avatar" />
          </div>
          <button
            className="infos-user-exit"
            type="button"
            onClick={returnToLogin}
          >
            <MdExitToApp />
          </button>
        </div>
      </div>

      <div className="list-games">
        <WaffleChart list={list} />
        {list.map((item) => (
          <div
            className={`game ${(gameVisible !== 0 && gameVisible !== item.id)
              ? 'dimmed' : ''}`}
            key={nextId()}
            role="button"
            onClick={() => toggleGameVisible(item.id)}
            tabIndex={0}
          >
            <div className="head">
              <div className={`note-bar ${note2color(item.note)}`} />
              <div className="logo">
                <img src={`${STEAM_IMG_URL}${item.id}/${item.img_logo_url}.jpg`} alt="steam game" />
              </div>
              <div className="icon">
                <img src={`${STEAM_IMG_URL}${item.id}/${item.img_icon_url}.jpg`} alt="steam game" />
              </div>
              <div className="title">
                {item.name}
                <div className="subtitle">
                  {item.subtitle}
                </div>
              </div>
              {gameVisible !== item.id ? (
                <div className="to-right">
                  <div className="tested-by">
                    {item.testedBy}
                  </div>
                  <div className={`note ${note2color(item.note)}`}>
                    {item.note !== null ? item.note : item.comment}
                  </div>
                </div>
              ) : null}
            </div>
            {gameVisible === item.id
              ? <GameDetails item={item} note2color={note2color} />
              : null}
          </div>
        ))}
      </div>
    </>
  );
};

ListGames.propTypes = {
  allMyGames: PropTypes.object.isRequired,
  setAllMyGames: PropTypes.func.isRequired,
};

export default ListGames;
