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
    if (feedback) feedback.textContent = correct ? 'Correct!' : 'Try again.';
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
