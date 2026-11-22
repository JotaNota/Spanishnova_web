/* Interactions already present in the SER lesson: quick question and conjugation table. */
document.addEventListener('click', function (event) {
  const option = event.target.closest('.sn-grammar-verb-lesson .grammar-lesson-option');
  if (option) {
    const block = option.closest('.grammar-lesson-question');
    if (!block) return;
    block.querySelectorAll('.grammar-lesson-option').forEach(function (button) {
      button.classList.remove('correct', 'incorrect');
    });
    const correct = option.dataset.answer === block.dataset.correctAnswer;
    option.classList.add(correct ? 'correct' : 'incorrect');
    const feedback = block.querySelector('.grammar-lesson-feedback');
    if (feedback) {
      feedback.textContent = correct ? 'Correct!' : 'Try again.';
      feedback.dataset.result = correct ? 'correct' : 'incorrect';
    }
    return;
  }
  const action = event.target.closest('.sn-grammar-verb-lesson [data-grammar-check], .sn-grammar-verb-lesson [data-grammar-reset]');
  if (!action) return;
  const group = action.closest('[data-grammar-checkable]');
  if (!group) return;
  if (action.hasAttribute('data-grammar-reset')) {
    group.querySelectorAll('input[data-answer]').forEach(function (input) { input.value = ''; });
    group.querySelectorAll('[data-feedback]').forEach(function (el) { el.textContent = ''; });
    return;
  }
  group.querySelectorAll('input[data-answer]').forEach(function (input) {
    const expected = (input.dataset.answer || '').trim().normalize('NFC').toLocaleLowerCase('es');
    const value = input.value.trim().normalize('NFC').toLocaleLowerCase('es');
    const feedback = input.closest('tr')?.querySelector('[data-feedback]');
    if (feedback) {
      feedback.textContent = value === expected ? '✓' : '✕';
      feedback.style.color = value === expected ? '#16734e' : '#ac3030';
    }
  });
});

/* Format paired Spanish/English examples without changing their wording. */
function spanishnovaFormatVerbExamples() {
  document.querySelectorAll('.sn-grammar-verb-lesson .grammar-lesson-card').forEach(function (card) {
    if (card.dataset.examplesFormatted === 'true') return;
    const title = card.querySelector('h3');
    const sentences = Array.from(card.querySelectorAll('strong'));
    if (!title || !sentences.length) return;

    const wholeText = card.textContent || '';
    const titleText = title.textContent || '';
    let cursor = wholeText.indexOf(titleText) + titleText.length;
    if (cursor < titleText.length) return;
    const pairs = [];
    for (let i = 0; i < sentences.length; i++) {
      const spanish = sentences[i].textContent || '';
      const start = wholeText.indexOf(spanish, cursor);
      if (start < 0) return;
      const end = start + spanish.length;
      const nextSpanish = sentences[i + 1] ? (sentences[i + 1].textContent || '') : '';
      const nextStart = nextSpanish ? wholeText.indexOf(nextSpanish, end) : wholeText.length;
      if (nextStart < end) return;
      const english = wholeText.slice(end, nextStart).trim();
      if (!english) return;
      pairs.push({ spanishNode: sentences[i].cloneNode(true), english: english });
      cursor = nextStart;
    }
    const rows = pairs.map(function (pair) {
      const row = document.createElement('div');
      row.className = 'grammar-lesson-example-row';
      row.appendChild(pair.spanishNode);
      row.appendChild(document.createTextNode(' — '));
      const translation = document.createElement('span');
      translation.className = 'grammar-lesson-example-translation';
      translation.textContent = pair.english;
      row.appendChild(translation);
      return row;
    });
    card.replaceChildren(title, ...rows);
    card.dataset.examplesFormatted = 'true';
  });
}
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', spanishnovaFormatVerbExamples);
} else {
  spanishnovaFormatVerbExamples();
}
