import { gsap } from 'gsap';

import { blindsOpen } from './blinds.js';
import { MOBILE, MOTION } from '../utils/media.js';

/* Сцена подвала: полосы цвета предыдущей секции расходятся и открывают футер,
   пока он выезжает снизу. Раскрытие привязано к его собственному появлению
   в кадре, а не к пину: подвал ниже экрана не занимает, закреплять нечего,
   да и после него страница заканчивается — прокручивать нечего.

   Раскрытие начинается, когда подвал уже показался из-за нижнего края, и
   заканчивается раньше конца страницы: так весь разъезд полос виден на экране,
   а не наполовину за кадром. Совпадает по времени с уездом СТА — переход
   между блоками идёт без остановки.

   На мобильном окно раскрытия сдвинуто ниже по экрану на MOBILE_DELAY:
   подвал начинает открываться позже, длина хода та же. */

/* Отметки экрана, между которыми расходятся полосы, в процентах его высоты. */
const OPEN_FROM = 90;
const OPEN_TO = 40;

/* На сколько процентов экрана позже открывается подвал на мобильном. */
const MOBILE_DELAY = 10;

export function initFooter() {
  const footer = document.querySelector('[data-footer]');
  const bars = footer ? [...footer.querySelectorAll('[data-blind]')] : [];

  if (!footer || !bars.length) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add({ motion: MOTION, mobile: MOBILE }, (ctx) => {
      const { motion, mobile } = ctx.conditions;

      if (!motion) return;

      const delay = mobile ? MOBILE_DELAY : 0;

      footer.dataset.scene = 'on';

      /* Твин один, длительность у него условная: scrub всё равно растягивает
         её на диапазон триггера. Держим в пикселях прокрутки, как в остальных
         сценах, — так число читается в тех же единицах. */
      gsap.to(bars, {
        ...blindsOpen(footer.offsetHeight),
        ease: 'none',
        scrollTrigger: {
          trigger: footer,
          start: `top ${OPEN_FROM - delay}%`,
          end: `top ${OPEN_TO - delay}%`,
          scrub: true,
          invalidateOnRefresh: true
        }
      });

      return () => delete footer.dataset.scene;
    });
  }, footer);

  return () => context.revert();
}
