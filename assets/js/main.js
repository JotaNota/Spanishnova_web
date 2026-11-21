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
  const emailTrigger = contact.querySelector('.sn-contact-email-trigger');
  const panel = contact.querySelector('.sn-contact-panel');
  const panelClose = contact.querySelector('.sn-contact-panel-close');
  const form = contact.querySelector('.sn-contact-form');
  const feedback = contact.querySelector('.sn-contact-feedback');
  const submit = contact.querySelector('.sn-contact-submit');
  const nameField = contact.querySelector('#sn-contact-name');
  if (!toggle || !options || !emailTrigger || !panel || !panelClose || !form || !feedback || !submit) return;

  function closePanel(restoreFocus) {
    const wasOpen = !panel.hidden;
    panel.hidden = true;
    emailTrigger.setAttribute('aria-expanded', 'false');
    if (wasOpen && restoreFocus) toggle.focus();
  }

  function setOpen(open) {
    options.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close contact options' : 'Open contact options');
    if (!open) closePanel(false);
  }

  toggle.addEventListener('click', function () {
    setOpen(options.hidden);
  });

  document.querySelectorAll('.sn-lesson-contact-button').forEach(function (button) {
    button.addEventListener('click', function () {
      emailTrigger.click();
    });
  });

  emailTrigger.addEventListener('click', function () {
    panel.hidden = false;
    options.hidden = true;
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Open contact options');
    emailTrigger.setAttribute('aria-expanded', 'true');
    nameField.focus();
  });

  panelClose.addEventListener('click', function () {
    closePanel(true);
  });

  document.addEventListener('click', function (event) {
    if (!contact.contains(event.target)) {
      closePanel(false);
      setOpen(false);
    }
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && (!options.hidden || !panel.hidden)) {
      closePanel(false);
      setOpen(false);
      toggle.focus();
    }
  });

  form.addEventListener('submit', async function (event) {
    event.preventDefault();
    if (!form.reportValidity() || submit.disabled) return;

    submit.disabled = true;
    feedback.textContent = 'Sending…';
    try {
      const response = await fetch(form.dataset.endpoint, {
        method: 'POST',
        body: new FormData(form),
        credentials: 'same-origin'
      });
      const result = await response.json();
      if (!response.ok || !result.success) {
        throw new Error(result.data?.message || 'Message could not be sent. Please try again.');
      }
      form.reset();
      feedback.textContent = 'Message sent. Thank you!';
    } catch (error) {
      feedback.textContent = error.message || 'Message could not be sent. Please try again.';
    } finally {
      submit.disabled = false;
    }
  });
})();
