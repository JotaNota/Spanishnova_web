document.addEventListener('click', function (event) {
  if (!event.target.matches('.sn-conj-check')) return;

  const practice = event.target.closest('.sn-conjugation-practice');
  if (!practice) return;

  practice.querySelectorAll('input[data-answer]').forEach(function (input) {
    const row = input.closest('tr');
    const feedback = row.querySelector('.sn-conj-feedback');

    const expectedRaw = input.dataset.answer.trim();
    const expected = expectedRaw.toLowerCase();
    const value = input.value.trim().toLowerCase();

    if (value === expected) {
      feedback.innerHTML = '<span class="sn-feedback-symbol is-correct">✓</span>';
    } else {
      feedback.innerHTML = '<span class="sn-feedback-symbol is-wrong">✕</span> <strong>' + expectedRaw + '</strong>';
    }
  });
});