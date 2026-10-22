import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { nextSection } from '../utils/siblings.js';
import { MOTION } from '../utils/media.js';
import { createVideoScrub } from '../utils/video-scrub.js';

/* Экскурсия (tour): липкий первый экран стоит на месте, а видео в нём
   (data-tour-video) идёт от прокрутки — как прогулка по клинике. Первый
   кадр — на старте, последний — когда снизу начинает подниматься следующая
   секция; дальше секцию разводит «Разъезд».

   Чтобы экскурсии было где пройти, у секции появляется отступ снизу длиной
   в TOUR_SHARE экранов: скрипт ставит его через --tour-length (правило
   в layout/section.css), без сцены его нет. Сцену поднимать до разъезда:
   его старт считается от следующей секции, которую этот отступ сдвигает.

   Описание сцен и их имена — в ANIMATIONS.md. */

/* Длина экскурсии — в экранах прокрутки. */
const TOUR_SHARE = 2.5;

export function initTour() {
  const contexts = [...document.querySelectorAll('[data-tour]')]
    .map(createTour)
    .filter(Boolean);

  if (!contexts.length) return;

  return () => contexts.forEach((context) => context.revert());
}

function createTour(section) {
  const video = section.querySelector('[data-tour-video]');
  const next = nextSection(section);

  if (!video || !next) return;

  const applyLength = () =>
    section.style.setProperty('--tour-length', `${window.innerHeight * TOUR_SHARE}px`);

  return gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add(MOTION, () => {
      const scrub = createVideoScrub(video);

      section.dataset.tour = 'on';
      applyLength();

      ScrollTrigger.create({
        trigger: section,
        start: 'top top',
        endTrigger: next,
        end: 'top bottom',
        invalidateOnRefresh: true,
        onRefreshInit: applyLength,
        onUpdate: ({ progress }) => scrub.seek(progress)
      });

      return () => {
        scrub.destroy();
        section.dataset.tour = '';
        section.style.removeProperty('--tour-length');
      };
    });
  }, section);
}
