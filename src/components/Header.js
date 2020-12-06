import React from 'react';

const Header = () => (
  <div className="header">
    <div className="app-name">
      <span className="header-logo canardvapeur" />
      <span>CANARD VAPEUR</span>
    </div>
    <div className="app-version">
      v
      {process.env.REACT_APP_VERSION}
    </div>
    <a href="https://twitter.com/NicolasW_GRAOU">
      <span className="header-logo twitter" />
    </a>
    <a href="https://twitter.com/NicolasW_GRAOU">
      <span className="header-logo github" />
    </a>
  </div>
);

export default Header;
