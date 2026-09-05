import { gsap } from 'gsap';

import { TABLET } from '../utils/media.js';

/* Лента результатов: стрелки сдвигают ленту на карточку. Дальше последней
   карточки, прижатой к правому краю секции, лента не едет, поэтому последний
   шаг может быть короче; на краях стрелка гаснет. Разметка рассчитана
   на Repeater: карточек любое число, если все помещаются — стрелки неактивны.
   Без скрипта лента прокручивается нативно (results.css).

   Ниже 1024 лента центрирована: активная карточка стоит посередине экрана
   и отмечена aria-current (её растит result-card.css), стрелки ходят
   по карточкам от первой до последней. */

const DURATION = 0.5;

export function initResults() {
  document.querySelectorAll('[data-results]').forEach(setup);
}

function setup(section) {
  const viewport = section.querySelector('[data-results-viewport]');
  const track = section.querySelector('[data-results-track]');
  const previous = section.querySelector('[data-results-prev]');
  const next = section.querySelector('[data-results-next]');
  const cards = track ? [...track.children] : [];

  if (!viewport || !track || !previous || !next || cards.length < 2) return;

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const tablet = window.matchMedia(TABLET);
  let index = 0;

  const step = () => cards[0].offsetWidth + (parseFloat(getComputedStyle(track).columnGap) || 0);

  /* Сколько ленты видно: от её левого края до правого поля секции. */
  const room = () => {
    const edge = section.getBoundingClientRect().right - (parseFloat(getComputedStyle(section).paddingRight) || 0);

    return edge - viewport.getBoundingClientRect().left;
  };

  const limit = () => Math.max(0, track.scrollWidth - room());
  const last = () => (tablet.matches ? cards.length - 1 : Math.ceil(limit() / step()));

  /* Сдвиг ленты: на десктопе — на карточку, до упора; на планшете — так,
     чтобы середина активной карточки встала в середину окна. */
  const centre = (card) =>
    card.offsetLeft - viewport.offsetLeft + card.offsetWidth / 2 - viewport.clientWidth / 2;
  const offset = (i) => (tablet.matches ? centre(cards[i]) : Math.min(i * step(), limit()));

  const render = (animate) => {
    index = Math.min(Math.max(index, 0), last());
    previous.disabled = index === 0;
    next.disabled = index >= last();

    cards.forEach((card, i) => {
      if (tablet.matches && i === index) {
        card.setAttribute('aria-current', 'true');
      } else {
        card.removeAttribute('aria-current');
      }
    });

    gsap.to(track, {
      x: -offset(index),
      duration: animate && !reduceMotion.matches ? DURATION : 0,
      ease: 'power2.out',
      overwrite: true
    });
  };

  const go = (delta) => {
    index += delta;
    render(true);
  };

  previous.addEventListener('click', () => go(-1));
  next.addEventListener('click', () => go(1));

  new ResizeObserver(() => render(false)).observe(section);
  tablet.addEventListener('change', () => render(false));

  section.dataset.results = 'on';
  render(false);
}
