import React from 'react';

const Disclaimer = () => (
  <div className="disclaimer">
    <p>
      Cet outil est proposé « en l&#39;état » et son auteur décline toute responsabilité
      de tout ordre. De plus, ni Canard PC ni Steam ne sont affiliés ni sponsors, et leurs marques
      respectives leur appartiennent.
      <br />
      Aucune donnée n&#39;est stockée côté serveur, l&#39;identifiant Steam est envoyé
      puis les données retournées et stockées dans votre navigateur en cache.
      <br />
      Ce projet est en béta perpétuelle, et juste à titre expérimental.
      <br />
      Bisous.
    </p>
    <p>
      Les données utilisées pour CanardPC sont issues du magnifique travail de
      <a href="https://coincoinpc.herokuapp.com"> Codinjutsu.org </a>
      et de
      <a href="http://madll.free.fr/"> Mad LL </a>
      (bravo pour le boulot effectué et quand vous voulez pour discuter et mutualiser !)
    </p>
  </div>
);

export default Disclaimer;
