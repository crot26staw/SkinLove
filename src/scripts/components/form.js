/* Форма обратной связи до подключения Contact Form 7: проверяет обязательные
   поля, показывает ошибки по киту и вместо отправки выводит сообщение
   об успехе. Ошибка у поля снимается, как только в него что-то ввели;
   reset формы возвращает её в исходный вид. */

export function initForms() {
  const forms = [...document.querySelectorAll('[data-form]')];

  if (!forms.length) return;

  const cleanups = forms.map(setup);

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(form) {
  const fields = [...form.querySelectorAll('input[required]')];
  const status = form.querySelector('[data-form-status]');

  const errorOf = (field) => field.closest('label')?.querySelector('[data-form-error]');

  const mark = (field, invalid) => {
    field.setAttribute('aria-invalid', String(invalid));
    const error = errorOf(field);
    if (error) error.hidden = !invalid;
  };

  const validate = () => fields.map((field) => {
    const valid = field.checkValidity();
    mark(field, !valid);
    return valid;
  });

  const onSubmit = (event) => {
    event.preventDefault();

    const results = validate();
    const firstInvalid = fields[results.indexOf(false)];

    if (firstInvalid) {
      firstInvalid.focus({ preventScroll: true });
      return;
    }

    form.classList.add('form--sent');

    if (status) {
      status.hidden = false;
      status.focus({ preventScroll: true });
    }
  };

  const onInput = (event) => {
    if (event.target.getAttribute('aria-invalid') === 'true' && event.target.checkValidity()) {
      mark(event.target, false);
    }
  };

  /* Сброс возвращает поля на место: попап делает его при закрытии. */
  const onReset = () => {
    fields.forEach((field) => mark(field, false));
    form.classList.remove('form--sent');
    if (status) status.hidden = true;
  };

  form.addEventListener('submit', onSubmit);
  form.addEventListener('input', onInput);
  form.addEventListener('reset', onReset);

  return () => {
    form.removeEventListener('submit', onSubmit);
    form.removeEventListener('input', onInput);
    form.removeEventListener('reset', onReset);
  };
}
