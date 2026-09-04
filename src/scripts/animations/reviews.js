import { gsap } from 'gsap';

import { blindsOpen } from './blinds.js';
import { exitLength, openLength } from './timing.js';
import { previousSection } from '../utils/siblings.js';

/* Отзывы за уезжающей секцией: подложены под неё и открываются жалюзи.
   Своей фазы у отзывов нет, но держать себя на время уезда они должны сами —
   два пина на одном элементе конфликтуют, поэтому предыдущая сцена, увидев
   у отзывов жалюзи, удержание не ставит (holdNext: false). Полосы начинают
   расходиться, когда предыдущая секция уехала на BLINDS_AT своего хода —
   так переход идёт без остановки, — и заканчивают уже после её ухода.

   Отзывы без жалюзи (главная) сюда не попадают: их держит предыдущая сцена.

   Описание сцен и их имена — в ANIMATIONS.md. */

/* Доля уезда предыдущей секции, после которой полосы начинают расходиться. */
const BLINDS_AT = 0.5;

export function initReviewsBlinds() {
  const cleanups = [...document.querySelectorAll('[data-section="reviews"]')]
    .map(setup)
    .filter(Boolean);

  if (!cleanups.length) return;

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(section) {
  const bars = [...section.querySelectorAll('[data-blind]')];
  const previous = previousSection(section);

  if (!bars.length || !previous) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      section.dataset.scene = 'on';
      section.style.setProperty('--blinds-color', getComputedStyle(previous).backgroundColor);

      const pause = exitLength() * BLINDS_AT;
      const opening = openLength();

      gsap
        .timeline({
          defaults: { ease: 'none' },
          scrollTrigger: {
            trigger: section,
            start: 'top top',
            end: () => `+=${exitLength() * BLINDS_AT + openLength()}`,
            pin: true,
            scrub: true,
            invalidateOnRefresh: true
          }
        })
        .to(bars, blindsOpen(opening), pause);

      return () => {
        delete section.dataset.scene;
        section.style.removeProperty('--blinds-color');
      };
    });
  }, section);

  return () => context.revert();
}
