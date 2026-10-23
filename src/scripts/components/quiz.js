import { ScrollTrigger } from 'gsap/ScrollTrigger';

/* Анкета из нескольких шагов (data-quiz-step): виден один, «Продолжить»
   пускает дальше, только если на шаге что-то выбрано или заполнено, иначе
   показывает data-quiz-error. Счётчик «01/05» и полоса прогресса следуют
   за шагом. Последний шаг — поля контактов: их проверяет и отправляет
   components/form.js, как любую форму.

   На шаге может быть пара «значение — не определился»: поле data-quiz-value
   и флажок data-quiz-unknown. Флажок очищает поле, ввод в поле снимает флажок.

   «Другая ситуация» (data-quiz-other): плашка целиком переключает флажок,
   а выбранная показывает поле «своими словами» и ставит в него курсор.

   Высота секции меняется от шага к шагу — триггеры ниже пересчитываются. */

export function initQuiz() {
  document.querySelectorAll('[data-quiz]').forEach(setup);
}

function setup(form) {
  const steps = [...form.querySelectorAll('[data-quiz-step]')];
  const counter = form.querySelector('[data-quiz-counter]');
  const progress = form.querySelector('[data-quiz-progress]');
  const back = form.querySelector('[data-quiz-back]');
  const next = form.querySelector('[data-quiz-next]');

  if (!steps.length || !next) return;

  const total = String(steps.length).padStart(2, '0');
  let current = 0;

  const show = (index, { focus = true } = {}) => {
    current = index;

    steps.forEach((step, i) => {
      step.hidden = i !== index;
    });

    if (counter) counter.textContent = `${String(index + 1).padStart(2, '0')}/${total}`;
    progress?.style.setProperty('--quiz-progress', String((index + 1) / steps.length));
    if (back) back.hidden = index === 0;
    next.hidden = index === steps.length - 1;

    if (focus) steps[index].querySelector('.quiz__step-title')?.focus({ preventScroll: true });
    ScrollTrigger.refresh();
  };

  const isAnswered = (step) => {
    const choices = [...step.querySelectorAll('input[type="checkbox"], input[type="radio"]')];
    const values = [...step.querySelectorAll('[data-quiz-value]')];

    return choices.some((input) => input.checked) || values.some((input) => input.value.trim());
  };

  const setError = (step, visible) => {
    const error = step.querySelector('[data-quiz-error]');
    if (error) error.hidden = !visible;
  };

  next.addEventListener('click', () => {
    const step = steps[current];

    if (!isAnswered(step)) {
      setError(step, true);
      return;
    }

    setError(step, false);
    show(current + 1);
  });

  back?.addEventListener('click', () => show(current - 1));

  form.addEventListener('input', (event) => {
    const field = event.target;
    const step = field.closest('[data-quiz-step]');
    if (!step) return;

    if (field.matches('[data-quiz-value]') && field.value) {
      const unknown = step.querySelector('[data-quiz-unknown]');
      if (unknown) unknown.checked = false;
    }

    if (isAnswered(step)) setError(step, false);
  });

  form.addEventListener('change', (event) => {
    const field = event.target;
    const step = field.closest('[data-quiz-step]');
    if (!step) return;

    if (field.matches('[data-quiz-unknown]') && field.checked) {
      step.querySelectorAll('[data-quiz-value]').forEach((input) => {
        input.value = '';
        input.dispatchEvent(new Event('change'));
      });
    }

    if (field.matches('[data-quiz-other]')) {
      const other = field.closest('.quiz__option')?.querySelector('[data-quiz-other-field]');
      if (other && field.checked) other.focus();
      if (other && !field.checked) other.value = '';
    }

    if (isAnswered(step)) setError(step, false);
  });

  /* Плашка «Другая ситуация» — не label (внутри поле ввода), поэтому клик
     по ней переключает флажок вручную. */
  form.querySelectorAll('[data-quiz-other]').forEach((checkbox) => {
    const option = checkbox.closest('.quiz__option');

    option?.addEventListener('click', (event) => {
      if (event.target === checkbox || event.target.matches('[data-quiz-other-field]')) return;
      checkbox.click();
    });
  });

  /* Сброс формы (попап, повторная отправка) возвращает анкету к началу. */
  form.addEventListener('reset', () => {
    steps.forEach((step) => setError(step, false));
    show(0, { focus: false });
  });

  show(0, { focus: false });
}
