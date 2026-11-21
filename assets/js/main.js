document.addEventListener('click', function (event) {
  if (!event.target.matches('.sn-conj-check, .sn-conj-reset')) return;

  const practice = event.target.closest('.sn-conjugation-practice');
  if (!practice) return;

  if (event.target.matches('.sn-conj-reset')) {
    practice.querySelectorAll('input[data-answer]').forEach(function (input) {
      input.value = '';
    });

    practice.querySelectorAll('.sn-conj-feedback').forEach(function (feedback) {
      feedback.innerHTML = '';
    });

    return;
  }

  practice.querySelectorAll('input[data-answer]').forEach(function (input) {
    const row = input.closest('tr');
    const feedback = row.querySelector('.sn-conj-feedback');

    const expectedRaw = input.dataset.answer.trim();
    const expected = expectedRaw.toLowerCase();
    const value = input.value.trim().toLowerCase();

    if (value === expected) {
      feedback.innerHTML = '<span class="sn-feedback-symbol is-correct">&#10003;</span>';
    } else {
      feedback.innerHTML = '<span class="sn-feedback-symbol is-wrong">&#10005;</span> <strong>' + expectedRaw + '</strong>';
    }
  });
});

(function () {
  const contact = document.querySelector('[data-sn-contact]');
  if (!contact) return;
  const toggle = contact.querySelector('.sn-contact-toggle');
  const options = contact.querySelector('.sn-contact-options');
  if (!toggle || !options) return;

  function setOpen(open) {
    options.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close contact options' : 'Open contact options');
  }

  toggle.addEventListener('click', function () {
    setOpen(options.hidden);
  });

  document.addEventListener('click', function (event) {
    if (!contact.contains(event.target)) setOpen(false);
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && !options.hidden) {
      setOpen(false);
      toggle.focus();
    }
  });
})();
