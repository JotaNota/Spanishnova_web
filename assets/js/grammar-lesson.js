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
