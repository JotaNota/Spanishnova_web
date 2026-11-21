(function () {
  var questions = document.querySelectorAll('.grammar-lesson-question');

  questions.forEach(function (question) {
    var correctAnswer = question.getAttribute('data-correct-answer');
    var feedback = question.querySelector('.grammar-lesson-feedback');
    var options = question.querySelectorAll('.grammar-lesson-option');

    if (!correctAnswer || !feedback || !options.length) {
      return;
    }

    options.forEach(function (option) {
      option.classList.remove(
        'grammar-lesson-option--correct',
        'grammar-lesson-option--incorrect',
        'grammar-lesson-option--selected'
      );
    });
    feedback.textContent = '';

    options.forEach(function (option) {
      option.addEventListener('click', function () {
        var selectedAnswer = option.getAttribute('data-answer');
        var isCorrect = selectedAnswer === correctAnswer;
        var wasMarked = option.classList.contains('grammar-lesson-option--correct') ||
          option.classList.contains('grammar-lesson-option--incorrect') ||
          option.classList.contains('grammar-lesson-option--selected');

        options.forEach(function (item) {
          item.classList.remove(
            'grammar-lesson-option--correct',
            'grammar-lesson-option--incorrect',
            'grammar-lesson-option--selected'
          );
        });

        if (wasMarked) {
          feedback.textContent = '';
          return;
        }

        if (isCorrect) {
          option.classList.add('grammar-lesson-option--correct');
          feedback.textContent = 'Correct!';
          return;
        }

        option.classList.add('grammar-lesson-option--incorrect');
        feedback.textContent = 'Try again.';
      });
    });
  });

  var checkableBlocks = document.querySelectorAll('[data-grammar-checkable]');

  checkableBlocks.forEach(function (block) {
    var checkButton = block.querySelector('[data-grammar-check]');
    var resetButton = block.querySelector('[data-grammar-reset]');
    var inputs = block.querySelectorAll('input[type="text"][data-answer]');

    if (!inputs.length) {
      return;
    }

    var normalizeAnswer = function (value) {
      return value.trim().toLocaleLowerCase();
    };

    if (checkButton) {
      checkButton.addEventListener('click', function () {
        inputs.forEach(function (input) {
          var expectedAnswer = normalizeAnswer(input.getAttribute('data-answer') || '');
          var currentAnswer = normalizeAnswer(input.value);
          var row = input.closest('tr');
          var feedback = row ? row.querySelector('[data-feedback]') : null;

          if (!feedback) {
            return;
          }

          feedback.textContent = currentAnswer && currentAnswer === expectedAnswer ? 'Correct!' : 'Try again.';
        });
      });
    }

    if (resetButton) {
      resetButton.addEventListener('click', function () {
        inputs.forEach(function (input) {
          var row = input.closest('tr');
          var feedback = row ? row.querySelector('[data-feedback]') : null;

          input.value = '';

          if (feedback) {
            feedback.textContent = '';
          }
        });
      });
    }
  });
})();

/* Progressive enhancement for the "Read and practice" profile list.
   If the expected headings and bilingual pairs are absent, leave the lesson unchanged. */
(function () {
  function linesFrom(node) {
    var clone = node.cloneNode(true);
    clone.querySelectorAll('br').forEach(function (br) {
      br.replaceWith(document.createTextNode('\n'));
    });
    return (clone.textContent || '').split(/\n+/).map(function (s) { return s.trim(); }).filter(Boolean);
  }
  function isProfile(s) { return /^\d+[.)]\s+\S/.test(s); }
  function commonContainer(first, last, heading) {
    var el = first.parentElement;
    while (el && el !== heading.parentElement) {
      if (el.contains(last)) return el;
      el = el.parentElement;
    }
    return null;
  }
  document.querySelectorAll('.sn-grammar-page .sn-post-content h2').forEach(function (heading) {
    if (heading.textContent.trim().toLowerCase() !== 'read and practice') return;
    var section = heading.closest('section') || heading.parentElement;
    if (!section) return;
    var nodes = Array.from(section.querySelectorAll('h3,h4,p,li'));
    var headers = nodes.filter(function (n) { return isProfile((n.textContent || '').trim()) && linesFrom(n).length === 1; });
    if (headers.length < 2) return;
    var container = commonContainer(headers[0], headers[headers.length - 1], heading);
    if (!container || container.contains(heading)) return;
    var profiles = [];
    var active = null;
    var unsupportedLine = false;
    container.querySelectorAll('h3,h4,p,li').forEach(function (node) {
      if (node.closest('li') && node.tagName !== 'LI') return;
      var strings = linesFrom(node);
      if (strings.length === 1 && isProfile(strings[0])) {
        active = { name: strings[0].replace(/^\d+[.)]\s*/, ''), pairs: [] };
        profiles.push(active);
        return;
      }
      if (!active) return;
      strings.forEach(function (line) {
        var match = line.match(/^(.+?)\s+[—–]\s+(.+)$/);
        if (match) active.pairs.push([match[1].trim(), match[2].trim()]);
        else unsupportedLine = true;
      });
    });
    if (unsupportedLine || profiles.length < 2 || profiles.some(function (p) { return p.pairs.length < 2; })) return;
    var cards = document.createElement('div');
    cards.className = 'sn-read-practice-cards';
    profiles.forEach(function (profile, index) {
      var article = document.createElement('article');
      article.className = 'sn-reading-profile';
      var header = document.createElement('div');
      header.className = 'sn-reading-profile-header';
      var names = document.createElement('div');
      var kicker = document.createElement('span');
      kicker.className = 'sn-reading-profile-kicker';
      kicker.textContent = 'PROFILE ' + String(index + 1).padStart(2, '0');
      var name = document.createElement('h3');
      name.className = 'sn-reading-profile-name';
      name.textContent = profile.name;
      names.append(kicker, name);
      var icon = document.createElement('span');
      icon.className = 'sn-reading-profile-icon';
      icon.setAttribute('aria-hidden', 'true');
      icon.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"><path d="M12 6c-2.7-1.7-5.4-1.8-9-1v13c3.6-.8 6.3-.7 9 1 2.7-1.7 5.4-1.8 9-1V5c-3.6-.8-6.3-.7-9 1z"/><path d="M12 6v13"/></svg>';
      header.append(names, icon);
      var languages = document.createElement('div');
      languages.className = 'sn-reading-language-head';
      ['ESPAÑOL', 'ENGLISH'].forEach(function (label) {
        var span = document.createElement('span');
        span.textContent = label;
        languages.appendChild(span);
      });
      article.append(header, languages);
      profile.pairs.forEach(function (pair) {
        var row = document.createElement('div');
        row.className = 'sn-reading-pair';
        pair.forEach(function (value, i) {
          var column = document.createElement('div');
          column.className = i === 0 ? 'sn-reading-pair-es' : 'sn-reading-pair-en';
          column.textContent = value;
          row.appendChild(column);
        });
        article.appendChild(row);
      });
      cards.appendChild(article);
    });
    container.parentNode.insertBefore(cards, container.nextSibling);
    container.hidden = true;
    section.classList.add('sn-read-practice');
  });
})();
