import { gsap } from 'gsap';

import { lockScroll } from '../utils/smooth-scroll.js';

/* Попап: открывается кнопками с data-modal-open (значение — id попапа),
   закрывается кнопкой, кликом по подложке и Escape. Пока открыт, прокрутка
   остановлена, а всё вокруг помечено inert. Фокус уходит в первое поле
   и возвращается на кнопку, которая открыла. При закрытии форма внутри
   сбрасывается — следующий раз попап открывается чистым. */

const DURATION = 0.4;

export function initModals() {
  const modals = [...document.querySelectorAll('[data-modal]')];

  if (!modals.length) return;

  const cleanups = modals.map(setup);

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(modal) {
  const dialog = modal.querySelector('[data-modal-dialog]');
  const backdrop = modal.querySelector('[data-modal-backdrop]');
  const closers = [...modal.querySelectorAll('[data-modal-close]')];
  const openers = [...document.querySelectorAll(`[data-modal-open="${modal.id}"]`)];
  const around = [...document.querySelectorAll('body > :not([data-modal])')];
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  let opener = null;
  let tween = null;

  const isOpen = () => !modal.hidden;

  const open = (event) => {
    event?.preventDefault();
    tween?.kill();
    opener = event?.currentTarget ?? null;
    modal.hidden = false;
    around.forEach((element) => {
      element.inert = true;
    });
    lockScroll(true);

    if (!reduceMotion.matches) {
      tween = gsap
        .timeline()
        .fromTo(backdrop, { opacity: 0 }, { opacity: 1, duration: DURATION, ease: 'power2.out' }, 0)
        .fromTo(
          dialog,
          { opacity: 0, y: 24 },
          { opacity: 1, y: 0, duration: DURATION, ease: 'power2.out', clearProps: 'transform' },
          0.05
        );
    }

    (modal.querySelector('input, textarea, select') ?? closers.at(-1))?.focus({ preventScroll: true });
  };

  const close = () => {
    if (!isOpen()) return;
    tween?.kill();

    const finish = () => {
      modal.hidden = true;
      modal.querySelector('form')?.reset();
      gsap.set([backdrop, dialog], { clearProps: 'opacity,transform' });
      around.forEach((element) => {
        element.inert = false;
      });
      lockScroll(false);
      opener?.focus({ preventScroll: true });
      opener = null;
    };

    if (reduceMotion.matches) {
      finish();
    } else {
      tween = gsap.to([backdrop, dialog], { opacity: 0, duration: DURATION * 0.6, ease: 'power2.in', onComplete: finish });
    }
  };

  const onKeydown = (event) => {
    if (event.key === 'Escape' && isOpen()) close();
  };

  openers.forEach((button) => button.addEventListener('click', open));
  closers.forEach((button) => button.addEventListener('click', close));
  document.addEventListener('keydown', onKeydown);

  return () => {
    if (isOpen()) close();
    openers.forEach((button) => button.removeEventListener('click', open));
    closers.forEach((button) => button.removeEventListener('click', close));
    document.removeEventListener('keydown', onKeydown);
  };
}
