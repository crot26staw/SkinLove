import { gsap } from 'gsap';

import { DESKTOP, MOTION } from '../utils/media.js';

/* Шапка над героем с фото: инверсия (mix-blend-mode: difference) на пёстром
   снимке делает логотип серым и нечитаемым, поэтому секция может попросить
   шапку начать без инверсии — атрибутом data-header-plain. После прокрутки
   на PLAIN_UNTIL пикселей инверсия включается обратно, как на всех страницах. */

const PLAIN_UNTIL = 100;

/* Шапка с data-header-stuck (строка выездных услуг) после STICK_FROM
   пикселей прокрутки получает класс header--stuck — полную строку на фоне,
   и теряет его выше UNSTICK_AT. Зазор между порогами не даёт шапке
   дёргаться, когда прокрутка стоит около границы.

   На десктопе смена идёт не рывком: прежний вид уходит вверх и гаснет,
   раскладка меняется, новый вид выезжает сверху. Ниже 1024 и при
   «уменьшить движение» класс просто переключается (на планшете у шапки
   нет своего блока — закреплены отдельные элементы, двигать нечего). */
const STICK_FROM = 80;
const UNSTICK_AT = 20;

export function initHeader() {
  const header = document.querySelector('[data-header]');

  if (!header) return;

  if (header.hasAttribute('data-header-stuck')) bindStuck(header);

  const plainHero = document.querySelector('[data-header-plain]');

  if (!plainHero) return;

  let plain = null;

  const update = () => {
    const next = window.scrollY <= PLAIN_UNTIL;
    if (next === plain) return;
    plain = next;
    header.classList.toggle('header--plain', plain);
  };

  update();
  window.addEventListener('scroll', update, { passive: true });
}

function bindStuck(header) {
  const desktop = window.matchMedia(DESKTOP);
  const motion = window.matchMedia(MOTION);
  let stuck = window.scrollY > STICK_FROM;
  let timeline = null;

  header.classList.toggle('header--stuck', stuck);

  const swap = (next) => {
    timeline?.kill();
    gsap.set(header, { clearProps: 'opacity,transform' });

    if (!desktop.matches || !motion.matches) {
      header.classList.toggle('header--stuck', next);
      return;
    }

    timeline = gsap.timeline({ onComplete: () => gsap.set(header, { clearProps: 'opacity,transform' }) })
      .to(header, { opacity: 0, yPercent: -30, duration: 0.18, ease: 'power2.in' })
      .add(() => header.classList.toggle('header--stuck', next))
      .fromTo(header,
        { opacity: 0, yPercent: next ? -100 : -30 },
        { opacity: 1, yPercent: 0, duration: 0.5, ease: 'power3.out' });
  };

  const update = () => {
    const y = window.scrollY;
    const next = stuck ? y > UNSTICK_AT : y > STICK_FROM;

    if (next === stuck) return;
    stuck = next;
    swap(next);
  };

  window.addEventListener('scroll', update, { passive: true });
}
