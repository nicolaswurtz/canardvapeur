import React from 'react';

const Disclaimer = () => (
  <div className="disclaimer center-text">
    <p>
      Cet outil est proposé « en l&#39;état » et son auteur décline toute responsabilité
      de tout ordre. De plus, ni Canard PC ni Steam ne sont affiliés ni sponsors, et leurs marques
      respectives leur appartiennent.
      <br />
      Aucune donnée n&#39;est stockée côté serveur, l&#39;identifiant Steam est envoyé
      puis les données retournées et stockées dans votre navigateur en cache.
      <br />
    </p>
    <p>
      Les données utilisées pour CanardPC sont issues du magnifique travail de
      <a href="https://coincoinpc.herokuapp.com"> CoincoinPC </a>
      et de
      <a href="http://madll.free.fr/"> Mad LL </a>
      (bravo pour le boulot effectué et quand vous voulez pour discuter et mutualiser !)
    </p>
    <p>
      Ce projet est en béta perpétuelle, et juste à titre expérimental.
      <br />
      Bisous.
    </p>
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
);

export default Disclaimer;
