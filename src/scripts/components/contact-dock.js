import { gsap } from 'gsap';

/* Быстрая связь: кнопка с телефоном раскрывает над собой WhatsApp и звонок.
   Открывается и закрывается нажатием, закрывается также кликом мимо, уходом
   фокуса и Escape. Ход: кнопки по очереди, начиная с ближней, выезжают
   из-под переключателя; закрытие — тот же ход назад. Смена телефона на
   крестик — переходом в CSS по aria-expanded. При отключённых анимациях
   список появляется и прячется сразу. */
const DURATION = 0.4;

export function initContactDock() {
  const dock = document.querySelector('[data-contact-dock]');

  if (!dock) return;

  const button = dock.querySelector('[data-contact-dock-toggle]');
  const list = dock.querySelector('[data-contact-dock-list]');

  if (!button || !list) return;

  const items = [...list.querySelectorAll('[data-contact-dock-item]')];
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  let timeline = null;

  const isOpen = () => button.getAttribute('aria-expanded') === 'true';

  const finishClose = () => {
    list.hidden = true;
    timeline?.kill();
    timeline = null;
    gsap.set(items, { clearProps: 'opacity,transform' });
  };

  /* Кнопки выезжают из точки переключателя: сдвиг каждой — её расстояние до него. */
  const build = () => {
    const origin = button.getBoundingClientRect().top;

    return gsap
      .timeline({ defaults: { duration: DURATION, ease: 'power3.out' }, onReverseComplete: finishClose })
      .from(items, {
        y: (index, item) => origin - item.getBoundingClientRect().top,
        scale: 0.6,
        opacity: 0,
        stagger: { each: 0.06, from: 'end' },
      });
  };

  const open = () => {
    if (isOpen()) return;

    button.setAttribute('aria-expanded', 'true');
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
    if (!dock.contains(event.relatedTarget)) close();
  };

  const onPointerDown = (event) => {
    if (!dock.contains(event.target)) close();
  };

  const onKeydown = (event) => {
    if (event.key !== 'Escape' || !isOpen()) return;

    const hadFocus = dock.contains(document.activeElement);

    close();
    if (hadFocus) button.focus({ preventScroll: true });
  };

  button.addEventListener('click', onClick);
  dock.addEventListener('focusout', onFocusOut);
  document.addEventListener('pointerdown', onPointerDown);
  document.addEventListener('keydown', onKeydown);

  return () => {
    button.removeEventListener('click', onClick);
    dock.removeEventListener('focusout', onFocusOut);
    document.removeEventListener('pointerdown', onPointerDown);
    document.removeEventListener('keydown', onKeydown);
    button.setAttribute('aria-expanded', 'false');
    finishClose();
  };
}
