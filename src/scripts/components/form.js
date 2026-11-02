/* Форма заявки: проверяет обязательные поля, показывает ошибки по киту
   и отправляет заявку JSON'ом на адрес из action (REST-эндпоинт темы).
   Успех — сообщение form__status, ошибка — form__failure с текстом ответа
   сервера. В статической вёрстке action="#": отправки нет, сразу успех.
   Ошибка у поля снимается, как только в него что-то ввели; reset формы
   возвращает её в исходный вид.

   Поля с data-mask приводятся к формату на лету: в имя попадают только буквы,
   пробел и дефис. Если у поля есть data-error-invalid, этот текст
   показывается вместо стандартного, когда поле заполнено, но не проходит
   проверку. */

export function initForms() {
  const forms = [...document.querySelectorAll('[data-form]')];

  if (!forms.length) return;

  const cleanups = forms.map(setup);

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(form) {
  const fields = [...form.querySelectorAll('input[required]')];
  const status = form.querySelector('[data-form-status]');
  const failure = form.querySelector('[data-form-failure]');
  const submit = form.querySelector('[type="submit"]');
  const endpoint = form.getAttribute('action');
  const defaultFailure = failure?.textContent;
  let sending = false;

  const errorOf = (field) => field.closest('label')?.querySelector('[data-form-error]');
  const defaultErrors = new Map(fields.map((field) => [field, errorOf(field)?.textContent]));

  const mark = (field, invalid) => {
    field.setAttribute('aria-invalid', String(invalid));
    const error = errorOf(field);
    if (!error) return;

    const custom = field.dataset.errorInvalid;
    error.textContent = invalid && custom && field.value ? custom : defaultErrors.get(field);
    error.hidden = !invalid;
  };

  const validate = () => fields.map((field) => {
    const valid = field.checkValidity();
    mark(field, !valid);
    return valid;
  });

  const succeed = () => {
    form.classList.add('form--sent');

    if (status) {
      status.hidden = false;
      status.focus({ preventScroll: true });
    }
  };

  const fail = (message) => {
    if (!failure) return;

    failure.textContent = message || defaultFailure;
    failure.hidden = false;
  };

  const setSending = (value) => {
    sending = value;
    form.toggleAttribute('aria-busy', value);
    if (submit) submit.disabled = value;
  };

  const onSubmit = async (event) => {
    event.preventDefault();

    if (sending) return;

    const results = validate();
    const firstInvalid = fields[results.indexOf(false)];

    if (firstInvalid) {
      firstInvalid.focus({ preventScroll: true });
      return;
    }

    if (!endpoint || endpoint === '#') {
      succeed();
      return;
    }

    if (failure) failure.hidden = true;
    setSending(true);

    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(collect(form)),
      });
      const body = await response.json().catch(() => ({}));

      if (response.ok) {
        succeed();
      } else {
        fail(body.message);
      }
    } catch {
      fail();
    } finally {
      setSending(false);
    }
  };

  const onInput = (event) => {
    const field = event.target;

    applyMask(field);

    if (field.getAttribute('aria-invalid') === 'true' && field.checkValidity()) {
      mark(field, false);
    }
  };

  /* Сброс возвращает поля на место: попап делает его при закрытии. */
  const onReset = () => {
    fields.forEach((field) => mark(field, false));
    form.classList.remove('form--sent');
    if (status) status.hidden = true;
    if (failure) failure.hidden = true;
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

/* Поля формы объектом; повторяющиеся имена (галочки квиза) — массивом. */
function collect(form) {
  const data = {};

  new FormData(form).forEach((value, key) => {
    data[key] = key in data ? [].concat(data[key], value) : value;
  });

  return data;
}

function applyMask(field) {
  const mask = MASKS[field.dataset.mask];
  if (!mask) return;

  const { value, selectionStart } = field;
  const next = mask.format(value);
  if (next === value) return;

  /* Курсор ставим после того же по счёту значимого символа, что и до
     форматирования: так ввод в середине поля не прыгает в конец. */
  const before = mask.count(value.slice(0, selectionStart ?? value.length));
  field.value = next;
  field.setSelectionRange(mask.position(next, before), mask.position(next, before));
}

const MASKS = {
  name: {
    format: (value) => value.replace(/[^\p{L}\s-]/gu, ''),
    count: (value) => value.replace(/[^\p{L}\s-]/gu, '').length,
    position: (value, count) => count,
  },
};
