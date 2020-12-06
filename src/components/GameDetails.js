import React from 'react';
import PropTypes from 'prop-types';
import { STEAM_HEADER_IMG_URL, PICTURES_URL } from './consts';

const GameDetails = (props) => {
  const { item, note2color } = props;
  return (
    <>
      <div className="mobile-logo">
        <img src={`${STEAM_HEADER_IMG_URL}${item.id}/header.jpg`} alt="steam game" />
      </div>
      <div className="details">
        <img className="cpc-cover" src={`${PICTURES_URL}${item.numcpc}.jpg`} alt="game icon" />
        <div className="content">
          <div className="content-head">
            <div className="badge numcpc">{`N° ${item.numcpc}`}</div>
            <div className="badge year">{item.year}</div>
            {item.time !== 0
              ? <div className="badge humantime">{item.humantime}</div>
              : null}
          </div>
          <div className="description">
            {item.description}
          </div>
          <div className="testedBy">
            {item.testedBy}
            <img className="testedBy-picture" src={`${PICTURES_URL}${item.author_coincoin}.png`} alt="test author" />
            <div className={`note ${note2color(item.note)}`}>
              {item.note !== null ? item.note : item.comment}
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

GameDetails.propTypes = {
  item: PropTypes.object.isRequired,
  note2color: PropTypes.func.isRequired,
};

export default GameDetails;
