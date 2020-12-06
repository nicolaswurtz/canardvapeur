import React from 'react';
import nextId from 'react-id-generator';
import PropTypes from 'prop-types';
import { note2color, toNumber } from '../helpers';

const formatCubes = (list) => {
  const cubes = [];
  const resultContent = Object.values({ ...list })
    .sort((a, b) => toNumber(b.note) - toNumber(a.note));

  while (resultContent.length > 0) {
    cubes.push(
      <div className="row-color-cubes" key={nextId()}>
        {resultContent.splice(0, 7).map(
          (item) => (
            <div
              key={nextId()}
              className={`color-cube ${note2color(item.note)}`}
              title={item.name}
            />
          ),
        )}
      </div>,
    );
  }
  return cubes;
};

const WaffleChart = (props) => {
  const {
    list,
  } = props;

  return (
    <div className="container-color-cubes">
      {formatCubes(list).map((item) => item)}
    </div>
  );
};

WaffleChart.propTypes = {
  list: PropTypes.array.isRequired,
};

export default WaffleChart;
