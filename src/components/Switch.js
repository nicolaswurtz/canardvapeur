import React from 'react';
import PropTypes from 'prop-types';

const Switch = (props) => {
  const {
    id, label, isChecked, toggleChecked,
  } = props;
  return (
    <div className="switch">
      <label htmlFor={id}>
        {label}
      </label>
      <input
        id={id}
        type="checkbox"
        className="toggle"
        checked={isChecked}
        onChange={toggleChecked}
      />
    </div>
  );
};

Switch.propTypes = {
  id: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired,
  isChecked: PropTypes.bool.isRequired,
  toggleChecked: PropTypes.func.isRequired,
};
export default Switch;
