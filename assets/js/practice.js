/* Shared practice interactions. No inline lesson scripts. */
(function () {
  document.querySelectorAll('.sn-practice-lesson .sv-choice-card').forEach(function (card) {
    var expected = card.getAttribute('data-correct');
    var feedback = card.querySelector('.sv-choice-feedback');
    var buttons = card.querySelectorAll('.sv-choice-button');
    if (!feedback || !expected || !buttons.length) return;

    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        var selected = button.getAttribute('data-answer');
        buttons.forEach(function (candidate) {
          candidate.setAttribute('aria-pressed', candidate === button ? 'true' : 'false');
        });
        var correct = selected === expected;
        feedback.textContent = correct ? '¡Correcto!' : 'Inténtalo de nuevo.';
        feedback.setAttribute('data-result', correct ? 'correct' : 'incorrect');
      });
    });
  });
})();
