import { gsap } from 'gsap';

import { blindsOpen } from './blinds.js';
import { createStackScene } from './stack-scene.js';
import { screen } from './timing.js';
import { previousSection } from '../utils/siblings.js';

/* Направления: жалюзи на въезде → стопка → уезд.
   Секция приходит обычной прокруткой, и полосы расходятся, пока она входит
   в кадр, — как у подвала: к моменту закрепления блок уже открыт, и стопка
   начинается сразу, без паузы. Цвет полос берётся из фона предыдущей секции.
   Следующая секция («атмосфера») держит себя сама — её сцена начинается
   с паузы той же длины, что и уезд.

   Ниже 1024 стопка та же, только без уезда — его каркас ставит лишь
   на десктопе; слот под карточку 459 задаёт services.css. */
export function initServices() {
  const section = document.querySelector('[data-section="services"]');

  if (!section) return;

  const items = [...section.querySelectorAll('[data-services-item]')];
  const bars = [...section.querySelectorAll('[data-blind]')];
  const previous = previousSection(section);

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      if (!bars.length) return;

      if (previous) {
        section.style.setProperty('--blinds-color', getComputedStyle(previous).backgroundColor);
      }

      gsap.to(bars, {
        ...blindsOpen(screen()),
        ease: 'none',
        scrollTrigger: {
          trigger: section,
          start: 'top 90%',
          end: 'top top',
          scrub: true,
          invalidateOnRefresh: true
        }
      });

      return () => section.style.removeProperty('--blinds-color');
    });
  }, section);

  const stack = createStackScene({
    section,
    track: section.querySelector('[data-services-track]'),
    items,
    cards: items.map((item) => item.querySelector('[data-services-card]')),
    holdNext: false
  });

  return () => {
    stack?.();
    context.revert();
  };
}
