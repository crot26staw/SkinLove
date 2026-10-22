import { gsap } from 'gsap';

import { blindsOpen } from './blinds.js';
import { openLength } from './timing.js';
import { previousSection } from '../utils/siblings.js';
import { MOTION } from '../utils/media.js';

/* Акции открываются жалюзи на въезде, как направления: полосы цвета
   предыдущей секции расходятся, пока акции поднимаются от низа экрана
   к верху, и к этому моменту блок открыт. Секция не закрепляется.
   Описание сцены — в ANIMATIONS.md. */
export function initPromosBlinds() {
  const cleanups = [...document.querySelectorAll('[data-section="promos"]')]
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

    mm.add(MOTION, () => {
      section.dataset.scene = 'on';
      section.style.setProperty('--blinds-color', getComputedStyle(previous).backgroundColor);

      gsap.to(bars, {
        ...blindsOpen(openLength()),
        ease: 'none',
        scrollTrigger: {
          trigger: section,
          start: 'top 90%',
          end: 'top top',
          scrub: true,
          invalidateOnRefresh: true
        }
      });

      return () => {
        delete section.dataset.scene;
        section.style.removeProperty('--blinds-color');
      };
    });
  }, section);

  return () => context.revert();
}
