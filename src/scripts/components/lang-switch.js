/* Переключатель языка стоит слева от кнопки меню. Обе закреплены по
   отдельности (header.css объясняет почему), поэтому свой отступ справа
   переключатель считает от ширины кнопки: модуль кладёт её в
   --menu-toggle-width. Пока меню открыто, кнопка шире («Закрыть»), но
   переключатель остаётся на месте — ширина берётся только у закрытой.

   Сам выбор языка — заглушка до подключения мультиязычности в теме. */
export function initLangSwitch() {
  const switcher = document.querySelector('[data-lang-switch]');
  const toggle = document.querySelector('[data-menu-toggle]');

  if (!switcher || !toggle) return;

  const update = () => {
    if (toggle.getAttribute('aria-expanded') === 'true') return;

    switcher.style.setProperty('--menu-toggle-width', `${toggle.offsetWidth}px`);
  };

  new ResizeObserver(update).observe(toggle);
}
