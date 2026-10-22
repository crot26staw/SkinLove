import { MOTION } from '../utils/media.js';

/* Ряд акций: листается нативно (свайпом, тачпадом), стрелки сдвигают его
   на карточку; на краях стрелка гаснет. Разметка рассчитана на Repeater:
   карточек любое число, если все помещаются — обе стрелки неактивны.
   Ниже 1024 стрелок нет (promos.css). */
export function initPromos() {
  document.querySelectorAll('[data-promos]').forEach(setup);
}

function setup(section) {
  const viewport = section.querySelector('[data-promos-viewport]');
  const previous = section.querySelector('[data-promos-prev]');
  const next = section.querySelector('[data-promos-next]');
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
  viewport.addEventListener('scroll', render, { passive: true });
  new ResizeObserver(render).observe(viewport);

  render();
}
