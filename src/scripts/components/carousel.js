import { MOTION } from '../utils/media.js';
import { enableDragScroll } from '../utils/drag-scroll.js';

/* Ряд карточек (акции, отзывы): листается нативно (свайпом, тачпадом),
   стрелки сдвигают его на карточку; на краях стрелка гаснет. Разметка рассчитана на Repeater:
   карточек любое число, если все помещаются — обе стрелки неактивны.
   Ниже 1024 стрелок нет (promos.css, feedback.css): палец листает ряд
   нативно, мышь — протяжкой (utils/drag-scroll.js). */
export function initCarousels() {
  document.querySelectorAll('[data-carousel]').forEach(setup);
}

function setup(section) {
  const viewport = section.querySelector('[data-carousel-viewport]');
  const previous = section.querySelector('[data-carousel-prev]');
  const next = section.querySelector('[data-carousel-next]');
  const track = viewport?.firstElementChild;

  if (!viewport || !previous || !next || !track?.children.length) return;

  const motion = window.matchMedia(MOTION);

  const step = () =>
    track.firstElementChild.offsetWidth + (parseFloat(getComputedStyle(track).columnGap) || 0);

  const render = () => {
    const max = viewport.scrollWidth - viewport.clientWidth;

    previous.disabled = viewport.scrollLeft <= 1;
    next.disabled = viewport.scrollLeft >= max - 1;
  };

  const go = (direction) => {
    viewport.scrollBy({ left: direction * step(), behavior: motion.matches ? 'smooth' : 'auto' });
  };

  previous.addEventListener('click', () => go(-1));
  next.addEventListener('click', () => go(1));
  enableDragScroll(viewport);
  viewport.addEventListener('scroll', render, { passive: true });
  new ResizeObserver(render).observe(viewport);

  render();
}
