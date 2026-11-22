/* Formats existing WordPress vocabulary lists without changing post content. */
(function () {
  var root = document.querySelector('.sn-vocabulary-page .sn-post-content');
  if (!root) return;

  function normalize(value) {
    return (value || '').replace(/\s+/g, ' ').trim();
  }

  function nextElement(element) {
    var next = element.nextElementSibling;
    while (next && next.tagName === 'P' && !normalize(next.textContent)) {
      next = next.nextElementSibling;
    }
    return next;
  }

  function readPairs(list) {
    if (!list || !['UL', 'OL'].includes(list.tagName)) return null;
    var items = Array.from(list.children);
    if (!items.length || items.some(function (item) { return item.tagName !== 'LI'; })) return null;

    var pairs = items.map(function (item) {
      var strong = item.querySelector('strong');
      if (!strong) return null;
      var spanish = normalize(strong.textContent);
      var fullText = normalize(item.textContent);
      var remaining = fullText.slice(spanish.length).replace(/^\s*[-–—:]\s*/, '');
      if (!spanish || !remaining || remaining === fullText.slice(spanish.length)) return null;
      return [spanish, remaining];
    });
    return pairs.every(Boolean) ? pairs : null;
  }

  function buildCard(heading, list, pairs) {
    var card = document.createElement('section');
    card.className = 'sn-vocab-card';
    if (/frases comunes/i.test(heading.textContent)) card.classList.add('sn-vocab-card--sentences');

    var header = document.createElement('div');
    header.className = 'sn-vocab-headers';
    ['ESPAÑOL', 'ENGLISH'].forEach(function (language) {
      var label = document.createElement('span');
      label.textContent = language;
      header.appendChild(label);
    });

    var rows = document.createElement('div');
    rows.className = 'sn-vocab-rows';
    pairs.forEach(function (pair) {
      var row = document.createElement('div');
      row.className = 'sn-vocab-row';
      pair.forEach(function (phrase) {
        var column = document.createElement('span');
        column.textContent = phrase;
        row.appendChild(column);
      });
      rows.appendChild(row);
    });

    heading.parentNode.insertBefore(card, heading);
    card.appendChild(heading);
    card.append(header, rows);
    list.remove();
  }

  Array.from(root.querySelectorAll('h2')).forEach(function (heading) {
    var title = normalize(heading.textContent);
    if (title === 'Traducir') {
      heading.classList.add('sn-vocab-practice-heading');
      var wrapper = document.createElement('section');
      wrapper.className = 'sn-vocab-practice';
      heading.parentNode.insertBefore(wrapper, heading);
      var current = heading;
      while (current && (current === heading || !/^H2$/.test(current.tagName))) {
        var next = current.nextElementSibling;
        wrapper.appendChild(current);
        current = next;
      }
      return;
    }
    var list = nextElement(heading);
    var pairs = readPairs(list);
    if (!pairs) return;
    buildCard(heading, list, pairs);
  });

  /* The approved supermarket model ends after the translation exercises.
     Only hide the final dialogue when this specific source lesson is detected. */
  var isSupermarket = Array.from(root.querySelectorAll('.sn-vocab-card > h2')).some(function (heading) {
    return normalize(heading.textContent).toLowerCase() === 'objetos del supermercado';
  });
  if (!isSupermarket) return;
  Array.from(root.querySelectorAll('h2')).forEach(function (heading) {
    if (normalize(heading.textContent).toLowerCase() !== 'diálogo: en el supermercado') return;
    var node = heading;
    while (node) {
      var next = node.nextElementSibling;
      node.hidden = true;
      node = next;
    }
  });
})();
