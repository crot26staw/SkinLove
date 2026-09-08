import { gsap } from 'gsap';

import { lockScroll } from '../utils/smooth-scroll.js';
import { bindDirections } from '../utils/directions.js';

/* Меню: кнопка в шапке открывает оверлей под ней. Пока меню открыто,
   прокрутка страницы остановлена, а контент под оверлеем недоступен
   для фокуса (inert). Escape закрывает, фокус возвращается на кнопку.

   Список направлений с превью — общий хелпер utils/directions.js. */

const DURATION = 0.4;

export function initMenu() {
  const toggle = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-menu]');

  if (!toggle || !menu) return;

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const covered = [...document.querySelectorAll('main, footer')];
  let tween = null;

  const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';

  const open = () => {
    tween?.kill();
    toggle.setAttribute('aria-expanded', 'true');
    menu.hidden = false;
    covered.forEach((element) => {
      element.inert = true;
    });
    lockScroll(true);

    if (!reduceMotion.matches) {
      tween = gsap.fromTo(menu, { opacity: 0 }, { opacity: 1, duration: DURATION, ease: 'power2.out' });
    }

    menu.querySelector('a')?.focus({ preventScroll: true });
  };

  const close = ({ restoreFocus = true } = {}) => {
    tween?.kill();
    toggle.setAttribute('aria-expanded', 'false');

    const finish = () => {
      menu.hidden = true;
      gsap.set(menu, { clearProps: 'opacity' });
      covered.forEach((element) => {
        element.inert = false;
      });
      lockScroll(false);
      if (restoreFocus) toggle.focus({ preventScroll: true });
    };

    if (reduceMotion.matches) {
      finish();
    } else {
      tween = gsap.to(menu, { opacity: 0, duration: DURATION * 0.75, ease: 'power2.in', onComplete: finish });
    }
  };

  const onToggle = () => (isOpen() ? close() : open());
  const onKeydown = (event) => {
    if (event.key === 'Escape' && isOpen()) close();
  };

  toggle.addEventListener('click', onToggle);
  document.addEventListener('keydown', onKeydown);

  const unbindDirections = setupDirections(menu);

  return () => {
    if (isOpen()) close({ restoreFocus: false });
    toggle.removeEventListener('click', onToggle);
    document.removeEventListener('keydown', onKeydown);
    unbindDirections?.();
  };
}

function setupDirections(menu) {
  return bindDirections({
    list: menu.querySelector('[data-menu-directions]'),
    links: [...menu.querySelectorAll('[data-menu-direction]')],
    preview: menu.querySelector('[data-menu-preview]'),
    hearts: [...menu.querySelectorAll('[data-menu-heart]')],
    currentClass: 'site-menu__heart--current',
  });
}
