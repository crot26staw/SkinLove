/* Форма обратной связи до подключения Contact Form 7: проверяет обязательные
   поля, показывает ошибки по киту и вместо отправки выводит сообщение
   об успехе. Ошибка у поля снимается, как только в него что-то ввели;
   reset формы возвращает её в исходный вид.

   Поля с data-mask приводятся к формату на лету: в имя попадают только буквы,
   пробел и дефис, телефон собирается как 8-333-333-33-33 (плюс и семёрка
   в начале заменяются на восьмёрку). Если у поля есть data-error-invalid,
   этот текст показывается вместо стандартного, когда поле заполнено,
   но не проходит проверку. */

const PHONE_LENGTH = 11;
const PHONE_GROUPS = [1, 3, 3, 2, 2];

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

function applyMask(field) {
  const mask = MASKS[field.dataset.mask];
  if (!mask) return;

  const { value, selectionStart } = field;
  const next = mask.format(value);
  if (next === value) return;

  /* Курсор ставим после того же по счёту значимого символа, что и до
     форматирования: так ввод в середине поля не прыгает в конец. */
  const before = mask.count(value.slice(0, selectionStart ?? value.length)) + mask.shift(value);
  field.value = next;
  field.setSelectionRange(mask.position(next, before), mask.position(next, before));
}

const MASKS = {
  name: {
    format: (value) => value.replace(/[^\p{L}\s-]/gu, ''),
    count: (value) => value.replace(/[^\p{L}\s-]/gu, '').length,
    shift: () => 0,
    position: (value, count) => count,
  },

  phone: {
    format: (value) => {
      const digits = phoneDigits(value);
      const groups = [];
      let offset = 0;

      for (const size of PHONE_GROUPS) {
        const group = digits.slice(offset, offset + size);
        if (!group) break;
        groups.push(group);
        offset += size;
      }

      return groups.join('-');
    },
    count: (value) => value.replace(/\D/g, '').length,
    /* Если восьмёрку добавили за пользователя, курсор сдвигается на неё. */
    shift: (value) => (/^\D*[78]/.test(value) || !/\d/.test(value) ? 0 : 1),
    position: (value, count) => {
      let seen = 0;
      for (let index = 0; index < value.length; index += 1) {
        if (/\d/.test(value[index])) seen += 1;
        if (seen === count) return index + 1;
      }
      return value.length;
    },
  },
};

function phoneDigits(value) {
  const digits = value.replace(/\D/g, '');
  if (!digits) return '';

  const rest = /^[78]/.test(digits) ? digits.slice(1) : digits;
  return `8${rest}`.slice(0, PHONE_LENGTH);
}
