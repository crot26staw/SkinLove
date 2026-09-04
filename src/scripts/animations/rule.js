import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { revealWindow } from './reveal.js';
import { screen } from './timing.js';

/* Черта (rule): линия рисуется от начала к концу, пока прокрутка выводит её
   на экран, а плюс рядом с ней крутится вместе с прокруткой страницы.

   Линия — компонент .rule (components/rule.css): её рисует псевдоэлемент,
   ход задаёт переменная --rule-progress. Без скрипта линия нарисована
   целиком. Где на прокрутке идёт ход, решает окно открытия (reveal.js) —
   то же, что у заливки заголовков.

   Плюсы крутятся все разом, от положения прокрутки: угол растёт с каждым
   пикселем и на месте не меняется. Центровка плюса в CSS задана свойством
   translate, а не transform, чтобы не спорить с поворотом.

   Описание сцен и их имена — в ANIMATIONS.md. */

/* На сколько градусов плюс поворачивается за экран прокрутки. */
const SPIN_PER_SCREEN = 180;

export function initRules() {
  const lines = [...document.querySelectorAll('[data-rule]')];
  const markers = [...document.querySelectorAll('[data-rule-marker]')];

  if (!lines.length && !markers.length) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      lines.forEach(drawLine);

      if (markers.length) {
        gsap.fromTo(
          markers,
          { rotation: 0 },
          {
            rotation: () => (ScrollTrigger.maxScroll(window) / screen()) * SPIN_PER_SCREEN,
            ease: 'none',
            scrollTrigger: { start: 0, end: 'max', scrub: true, invalidateOnRefresh: true }
          }
        );
      }
    });
  });

  return () => context.revert();
}

function drawLine(line) {
  const section = line.closest('[data-section]');

  if (!section) return;

  const { trigger, start, end, at, length } = revealWindow(section, line);

  gsap
    .timeline({
      defaults: { ease: 'none' },
      scrollTrigger: { trigger, start, end, scrub: true, invalidateOnRefresh: true }
    })
    .fromTo(line, { '--rule-progress': 0 }, { '--rule-progress': 1, duration: length }, at);
}
