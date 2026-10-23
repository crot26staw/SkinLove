/* Выбор даты и времени: кнопка-поле открывает плашку из месяца со стрелками
   и списка времени на выбранный день. Прошедшие дни и часы недоступны.
   Выбранное значение пишется в скрытое поле (data-date-picker-input)
   строкой «29.10.26, 19:00» и показывается в кнопке; поле получает событие
   input, чтобы форма могла на него отреагировать. Если поле очистили снаружи
   и прислали change, кнопка возвращает подсказку.

   Время берётся из data-times на корне — список через запятую, его задаёт
   редактор. Плашка закрывается после выбора времени, по Escape и по клику
   мимо. */

const DAY = 24 * 60 * 60 * 1000;

export function initDatePickers() {
  document.querySelectorAll('[data-date-picker]').forEach(setup);
}

function setup(root) {
  const toggle = root.querySelector('[data-date-picker-toggle]');
  const label = root.querySelector('[data-date-picker-value]');
  const input = root.querySelector('[data-date-picker-input]');
  const popup = root.querySelector('[data-date-picker-popup]');

  if (!toggle || !label || !input || !popup) return;

  const locale = document.documentElement.lang || 'ru';
  const placeholder = label.textContent;
  const times = (root.dataset.times || '').split(',').map((time) => time.trim()).filter(Boolean);
  const weekdays = weekdayNames(locale);

  const today = startOfDay(new Date());
  let view = startOfMonth(today);
  let selected = null;
  let time = null;

  popup.id ||= `date-picker-${Math.random().toString(36).slice(2, 8)}`;
  toggle.setAttribute('aria-controls', popup.id);

  const render = () => {
    popup.replaceChildren(monthPanel(view), timePanel());
  };

  /* Назад листать можно только до текущего месяца. */
  const monthPanel = (month) => {
    const panel = element('div', 'date-picker__panel');
    const title = capitalize(month.toLocaleDateString(locale, { month: 'long', year: 'numeric' }).replace(/\s?г\.$/, ''));

    panel.append(
      head(title, {
        prev: { disabled: month <= startOfMonth(today), label: 'Предыдущий месяц', onClick: () => shiftMonth(-1) },
        next: { label: 'Следующий месяц', onClick: () => shiftMonth(1) },
      }),
    );

    const names = element('div', 'date-picker__grid');
    weekdays.forEach((name) => names.append(element('span', 'date-picker__weekday', name)));

    const days = element('div', 'date-picker__grid');
    const offset = (month.getDay() + 6) % 7;
    const count = new Date(month.getFullYear(), month.getMonth() + 1, 0).getDate();

    for (let i = 0; i < offset; i += 1) days.append(element('span'));

    for (let day = 1; day <= count; day += 1) {
      const date = new Date(month.getFullYear(), month.getMonth(), day);
      const button = element('button', 'date-picker__cell', String(day).padStart(2, '0'));

      button.type = 'button';
      button.disabled = date < today;
      button.setAttribute('aria-label', date.toLocaleDateString(locale, { day: 'numeric', month: 'long', year: 'numeric' }));
      button.setAttribute('aria-pressed', String(isSameDay(date, selected)));
      button.addEventListener('click', () => pickDay(date));
      days.append(button);
    }

    panel.append(names, days);
    return panel;
  };

  const timePanel = () => {
    const panel = element('div', 'date-picker__panel');

    if (!selected) {
      panel.append(head('Время'), element('p', 'date-picker__hint', 'Сначала выберите день'));
      return panel;
    }

    const title = selected.toLocaleDateString(locale, { day: 'numeric', month: 'long', year: 'numeric' }).replace(/\s?г\.$/, '');

    panel.append(
      head(title, {
        prev: { disabled: selected <= today, label: 'Предыдущий день', onClick: () => pickDay(new Date(selected.getTime() - DAY)) },
        next: { label: 'Следующий день', onClick: () => pickDay(new Date(selected.getTime() + DAY)) },
      }),
    );

    const grid = element('div', 'date-picker__grid date-picker__grid--times');
    const now = new Date();

    times.forEach((value) => {
      const button = element('button', 'date-picker__cell', value);
      const [hours, minutes] = value.split(':').map(Number);
      const moment = new Date(selected.getFullYear(), selected.getMonth(), selected.getDate(), hours, minutes || 0);

      button.type = 'button';
      button.disabled = moment <= now;
      button.setAttribute('aria-pressed', String(value === time));
      button.addEventListener('click', () => pickTime(value));
      grid.append(button);
    });

    panel.append(grid);
    return panel;
  };

  const head = (title, arrows = {}) => {
    const row = element('div', 'date-picker__head');

    row.append(
      arrowButton('prev', arrows.prev),
      element('p', 'date-picker__title', title),
      arrowButton('next', arrows.next),
    );

    return row;
  };

  /* Панели пересобираются целиком, поэтому фокус возвращается на ту же
     по смыслу кнопку в новой разметке. */
  const shiftMonth = (step) => {
    view = new Date(view.getFullYear(), view.getMonth() + step, 1);
    render();
    popup.querySelector(`.date-picker__arrow--${step < 0 ? 'prev' : 'next'}:not(:disabled)`)?.focus();
  };

  const pickDay = (date) => {
    selected = startOfDay(date);
    time = null;

    /* Выбранный день должен быть на виду: стрелки панели времени могут
       увести его в соседний месяц — листаем месяц следом. */
    view = startOfMonth(selected);

    render();
    popup.querySelector('.date-picker__cell[aria-pressed="true"]')?.focus();
  };

  const pickTime = (value) => {
    time = value;

    const date = selected.toLocaleDateString(locale, { day: '2-digit', month: '2-digit', year: '2-digit' });
    input.value = `${date}, ${time}`;
    label.textContent = input.value;
    root.toggleAttribute('data-filled', true);
    input.dispatchEvent(new Event('input', { bubbles: true }));

    close();
    toggle.focus();
  };

  const open = () => {
    render();
    popup.hidden = false;
    toggle.setAttribute('aria-expanded', 'true');
    document.addEventListener('pointerdown', onOutside);
    document.addEventListener('keydown', onKey);
  };

  const close = () => {
    popup.hidden = true;
    toggle.setAttribute('aria-expanded', 'false');
    document.removeEventListener('pointerdown', onOutside);
    document.removeEventListener('keydown', onKey);
  };

  const onOutside = (event) => {
    if (!root.contains(event.target)) close();
  };

  const onKey = (event) => {
    if (event.key !== 'Escape') return;
    close();
    toggle.focus();
  };

  /* Поле очистили снаружи (например, «Не определился»). */
  const onReset = () => {
    if (input.value) return;
    selected = null;
    time = null;
    label.textContent = placeholder;
    root.toggleAttribute('data-filled', false);
  };

  toggle.addEventListener('click', () => (popup.hidden ? open() : close()));
  input.addEventListener('change', onReset);
}

function arrowButton(direction, options) {
  const button = element('button', `date-picker__arrow date-picker__arrow--${direction}`);

  button.type = 'button';
  button.innerHTML = '<svg class="date-picker__arrow-icon" viewBox="0 0 10.5 7.36396" fill="none" aria-hidden="true" focusable="false"><path d="M10.3536 4.03553C10.5488 3.84027 10.5488 3.52369 10.3536 3.32843L7.17157 0.146447C6.97631 -0.0488155 6.65973 -0.0488155 6.46447 0.146447C6.2692 0.341709 6.2692 0.658291 6.46447 0.853554L9.29289 3.68198L6.46447 6.51041C6.2692 6.70567 6.2692 7.02225 6.46447 7.21751C6.65973 7.41278 6.97631 7.41278 7.17157 7.21751L10.3536 4.03553ZM0 3.68198V4.18198H10V3.68198V3.18198H0V3.68198Z" fill="currentColor"/></svg>';

  if (!options) {
    button.hidden = true;
    button.tabIndex = -1;
    return button;
  }

  button.disabled = Boolean(options.disabled);
  button.setAttribute('aria-label', options.label);
  button.addEventListener('click', options.onClick);
  return button;
}

function element(tag, className, text) {
  const node = document.createElement(tag);
  if (className) node.className = className;
  if (text) node.textContent = text;
  return node;
}

function weekdayNames(locale) {
  const monday = new Date(2024, 0, 1);

  return Array.from({ length: 7 }, (_, i) =>
    new Date(monday.getTime() + i * DAY).toLocaleDateString(locale, { weekday: 'short' }),
  );
}

function startOfDay(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}

function startOfMonth(date) {
  return new Date(date.getFullYear(), date.getMonth(), 1);
}

function isSameDay(a, b) {
  return Boolean(b) && a.getTime() === b.getTime();
}

function capitalize(text) {
  return text.charAt(0).toUpperCase() + text.slice(1);
}
