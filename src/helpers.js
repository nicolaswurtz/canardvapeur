import { useState, useEffect } from 'react';

export const toNumber = (string) => (Number.isNaN(Number(string)) ? 0 : Number(string));

export const useFetch = (url) => {
  const [response, setresponse] = useState(null);
  const [error, setError] = useState(null);
  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetch(url);
        const json = await res.json();
        setresponse(json);
      } catch (e) {
        setError(e);
      }
    };
    fetchData();
  }, [url]);
  return { response, error };
};

export const searchFilter = (filter, list, setList, setAverageNote, switches) => {
  const filterLower = filter.toLowerCase();
  let resultContent = list.filter(
    (el) => el.name.toLowerCase().includes(filterLower)
      || el.editor.toLowerCase().includes(filterLower)
      || el.testedBy.toLowerCase().includes(filterLower),
  );

  if (switches.neverPlayed) {
    resultContent = resultContent.filter(
      (el) => el.time === 0,
    );
  }

  if (switches.sortByNote) {
    resultContent = resultContent.sort((a, b) => toNumber(b.note) - toNumber(a.note)
    || a.name.localeCompare(b.name));
  } else {
    resultContent = resultContent.sort((a, b) => a.name.localeCompare(b.name));
  }

  let nb = 0;
  let sum = 0;
  resultContent.forEach((item) => {
    if (item.note !== null
      && item.note !== ''
      && !Number.isNaN(Number(item.note))) {
      nb += 1;
      sum += Number(item.note);
    }
  });
  const averageNote = Math.round((sum / nb) * 10) / 10;
  setAverageNote(Number.isNaN(averageNote) ? 0 : averageNote);

  setList(resultContent);
};

export const note2color = (note) => {
  if (note === null || note === '' || Number.isNaN(Number(note))) {
    return 'noteNaN';
  }
  if (Number(note) > 10) {
    return 'noteMax';
  }
  return `note${note}`;
};
