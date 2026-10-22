import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { MOTION } from '../utils/media.js';

/* Этапы выезда, сцена «Прогресс»: колонка с номером этапа заливается тёмным
   сверху вниз, пока этап проходит середину экрана, — как полоса загрузки.
   Пройденные этапы остаются залитыми. Заливку рисует CSS по --process-progress
   (transform: scaleY, process.css), сцена только ведёт переменную. Номер
   светлеет, когда заливка его накрыла (process__number--lit).

   При отключённых анимациях заливки по ходу нет: колонка заливается целиком,
   когда этап дошёл до середины экрана.

   Описание сцен и их имена — в ANIMATIONS.md. */

const START = 'top center';
const END = 'bottom center';

export function initProcess() {
  const numbers = [...document.querySelectorAll('[data-process] [data-process-number]')];

  if (!numbers.length) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add(MOTION, () => {
      numbers.forEach((number) => {
        const index = number.firstElementChild;
        let cover = 0;

        gsap.fromTo(
          number,
          { '--process-progress': 0 },
          {
            '--process-progress': 1,
            ease: 'none',
            scrollTrigger: {
              trigger: number.parentElement,
              start: START,
              end: END,
              scrub: true,
              /* Доля колонки, которую надо залить, чтобы накрыть номер. */
              onRefresh: () => {
                cover = index ? (index.offsetTop + index.offsetHeight) / number.offsetHeight : 0;
              },
              onUpdate: (self) => {
                number.classList.toggle('process__number--lit', self.progress >= cover);
              }
            }
          }
        );
      });
    });

    mm.add('(prefers-reduced-motion: reduce)', () => {
      numbers.forEach((number) => {
        ScrollTrigger.create({
          trigger: number.parentElement,
          start: START,
          onEnter: () => fill(number, true),
          onLeaveBack: () => fill(number, false)
        });
      });
    });

    /* Класс ставит не GSAP, поэтому при откате снимается вручную. */
    return () => numbers.forEach((number) => number.classList.remove('process__number--lit'));
  });

  return () => context.revert();
}

function fill(number, on) {
  gsap.set(number, { '--process-progress': on ? 1 : 0 });
  number.classList.toggle('process__number--lit', on);
}
