import { gsap } from 'gsap';

import { blindsOpen } from './blinds.js';

/* Сцена подвала: полосы цвета предыдущей секции расходятся и открывают футер,
   пока он выезжает снизу. Раскрытие привязано к его собственному появлению
   в кадре, а не к пину: подвал ниже экрана не занимает, закреплять нечего,
   да и после него страница заканчивается — прокручивать нечего.

   Раскрытие начинается, когда подвал уже показался из-за нижнего края, и
   заканчивается раньше конца страницы: так весь разъезд полос виден на экране,
   а не наполовину за кадром. Совпадает по времени с уездом СТА — переход
   между блоками идёт без остановки. */
export function initFooter() {
  const footer = document.querySelector('[data-footer]');
  const bars = footer ? [...footer.querySelectorAll('[data-blind]')] : [];

  if (!footer || !bars.length) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      footer.dataset.scene = 'on';

      /* Твин один, длительность у него условная: scrub всё равно растягивает
         её на диапазон триггера. Держим в пикселях прокрутки, как в остальных
         сценах, — так число читается в тех же единицах. */
      gsap.to(bars, {
        ...blindsOpen(footer.offsetHeight),
        ease: 'none',
        scrollTrigger: {
          trigger: footer,
          start: 'top 90%',
          end: 'top 40%',
          scrub: true,
          invalidateOnRefresh: true
        }
      });

      return () => delete footer.dataset.scene;
    });
  }, footer);

  return () => context.revert();
}
