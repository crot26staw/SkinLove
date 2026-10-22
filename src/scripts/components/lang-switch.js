import { gsap } from 'gsap';

/* Переключатель языка стоит слева от кнопки меню. Обе закреплены по
   отдельности (header.css объясняет почему), поэтому свой отступ справа
   переключатель считает от ширины кнопки: модуль кладёт её в
   --menu-toggle-width. Пока меню открыто, кнопка шире («Закрыть»), но
   переключатель остаётся на месте — ширина берётся только у закрытой.

   Список языков открывается и закрывается нажатием на кнопку, закрывается
   также кликом мимо, уходом фокуса и Escape. Ход: тёмная пилюля ховера перетекает в плашку —
   нижние углы кнопки распрямляются, а список под ней разворачивается
   сверху вниз, и пункты опускаются на место. Закрытие — тот же ход назад.
   При отключённых анимациях список появляется и прячется сразу.

   Сами ссылки — заглушки до подключения мультиязычности в теме. */
const DURATION = 0.45;
const CORNERS = ['borderTopLeftRadius', 'borderTopRightRadius', 'borderBottomRightRadius', 'borderBottomLeftRadius'];

export function initLangSwitch() {
  const switcher = document.querySelector('[data-lang-switch]');
  const toggle = document.querySelector('[data-menu-toggle]');

  if (!switcher) return;

  const unbindDropdown = setupDropdown(switcher);

  if (!toggle) return unbindDropdown;

  const update = () => {
    if (toggle.getAttribute('aria-expanded') === 'true') return;

    switcher.style.setProperty('--menu-toggle-width', `${toggle.offsetWidth}px`);
  };

  const observer = new ResizeObserver(update);

  observer.observe(toggle);

  return () => {
    observer.disconnect();
    unbindDropdown?.();
  };
}

function setupDropdown(switcher) {
  const button = switcher.querySelector('[data-lang-switch-toggle]');
  const list = switcher.querySelector('[data-lang-switch-list]');

  if (!button || !list) return;

  const items = [...list.querySelectorAll('[data-lang-switch-item]')];
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  let timeline = null;

  const isOpen = () => button.getAttribute('aria-expanded') === 'true';

  const finishClose = () => {
    list.hidden = true;
    delete switcher.dataset.open;
    timeline?.kill();
    timeline = null;
    gsap.set(button, { clearProps: CORNERS.join(',') });
    gsap.set([list, ...items], { clearProps: 'clipPath,opacity,transform' });
  };

  /* Ход строится от открытого состояния из CSS: стартовые значения —
     пилюля ховера (скругление в половину высоты) и свёрнутый список. */
  const build = () => {
    const radius = `${button.offsetHeight / 2}px`;
    const pill = Object.fromEntries(CORNERS.map((corner) => [corner, radius]));

    return gsap
      .timeline({ defaults: { duration: DURATION, ease: 'power3.inOut' }, onReverseComplete: finishClose })
      .from(button, pill, 0)
      .from(list, { clipPath: 'inset(0% 0% 100% 0%)' }, 0)
      .from(items, { y: -8, opacity: 0, duration: DURATION * 0.7, ease: 'power2.out', stagger: 0.05 }, DURATION * 0.3);
  };

  const open = () => {
    if (isOpen()) return;

    button.setAttribute('aria-expanded', 'true');
    switcher.dataset.open = '';
    list.hidden = false;

    if (reduceMotion.matches) return;

    /* Повторное открытие посреди закрытия — тот же ход вперёд с текущей точки. */
    timeline ??= build();
    timeline.timeScale(1).play();
  };

  const close = () => {
    if (!isOpen()) return;

    button.setAttribute('aria-expanded', 'false');

    if (reduceMotion.matches || !timeline) {
      finishClose();
      return;
    }

    timeline.timeScale(1.4).reverse();
  };

  const onClick = () => (isOpen() ? close() : open());

  const onFocusOut = (event) => {
    if (!switcher.contains(event.relatedTarget)) close();
  };

  const onPointerDown = (event) => {
    if (!switcher.contains(event.target)) close();
  };

  const onKeydown = (event) => {
    if (event.key !== 'Escape' || !isOpen()) return;

    const hadFocus = switcher.contains(document.activeElement);

    close();
    if (hadFocus) button.focus({ preventScroll: true });
  };

  button.addEventListener('click', onClick);
  switcher.addEventListener('focusout', onFocusOut);
  document.addEventListener('pointerdown', onPointerDown);
  document.addEventListener('keydown', onKeydown);

  return () => {
    button.removeEventListener('click', onClick);
    switcher.removeEventListener('focusout', onFocusOut);
    document.removeEventListener('pointerdown', onPointerDown);
    document.removeEventListener('keydown', onKeydown);
    button.setAttribute('aria-expanded', 'false');
    finishClose();
  };
}
