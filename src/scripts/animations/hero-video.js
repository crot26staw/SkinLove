import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { nextSection } from '../utils/siblings.js';
import { MOTION } from '../utils/media.js';

/* Видео первого экрана идёт от прокрутки: первый кадр — на старте страницы,
   последний — когда следующая секция встала в верх экрана. Слой с видео
   закреплён под всей страницей (hero.css), поэтому ниже второй секции,
   где его уже закрыли, он прячется.

   Кадр ставится не на каждый тик прокрутки, а по одному: следующая перемотка
   уходит только когда предыдущая отрисована. Иначе перемотки копятся,
   браузер бросает промежуточные, и на ходу видео стоит, а кадр
   допрыгивает уже после остановки. */
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
      let target = 0;
      let pending = false;

      const seek = () => {
        if (video.readyState < HTMLMediaElement.HAVE_METADATA || video.seeking) {
          pending = true;
          return;
        }

        pending = false;

        const time = target * video.duration;
        if (Math.abs(video.currentTime - time) > 1 / 60) video.currentTime = time;
      };

      const onSeeked = () => pending && seek();

      video.addEventListener('seeked', onSeeked);
      video.addEventListener('loadedmetadata', seek, { once: true });

      ScrollTrigger.create({
        trigger: next,
        start: 'top bottom',
        end: 'top top',
        onUpdate: ({ progress }) => {
          target = progress;
          seek();
        }
      });

      return () => {
        video.removeEventListener('seeked', onSeeked);
        video.removeEventListener('loadedmetadata', seek);
      };
    });
  }, hero);

  return () => context.revert();
}
