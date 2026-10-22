import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { nextSection } from '../utils/siblings.js';
import { MOTION } from '../utils/media.js';
import { createVideoScrub } from '../utils/video-scrub.js';

/* Видео первого экрана идёт от прокрутки: первый кадр — на старте страницы,
   последний — когда следующая секция встала в верх экрана. Слой с видео
   закреплён под всей страницей (hero.css), поэтому ниже второй секции,
   где его уже закрыли, он прячется. */
export function initHeroVideo() {
  const hero = document.querySelector('[data-section="hero"]');
  const layer = hero?.querySelector('[data-hero-video]');
  const video = layer?.querySelector('video');
  const next = nextSection(hero);

  if (!hero || !layer || !video || !next) return;

  const context = gsap.context(() => {
    ScrollTrigger.create({
      trigger: hero,
      start: 'top bottom',
      endTrigger: next,
      end: 'bottom top',
      onToggle: ({ isActive }) => gsap.set(layer, { visibility: isActive ? 'visible' : 'hidden' })
    });

    const mm = gsap.matchMedia();

    mm.add(MOTION, () => {
      const scrub = createVideoScrub(video);

      ScrollTrigger.create({
        trigger: next,
        start: 'top bottom',
        end: 'top top',
        onUpdate: ({ progress }) => scrub.seek(progress)
      });

      return () => scrub.destroy();
    });
  }, hero);

  return () => context.revert();
}
