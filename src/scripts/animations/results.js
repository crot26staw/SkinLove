import { createRailScene } from './rail-scene.js';
import { exitLength } from './timing.js';
import { DESKTOP } from '../utils/media.js';

/* Результаты на десктопе: лента без уезда. Секция закреплена, карточки едут
   влево от прокрутки, пока последняя не встанет к правому полю секции; потом
   секция снимается с закрепления и уходит обычной прокруткой. Уезда нет:
   следующие за ней отзывы раскрывают жалюзи на въезде (reviews.js).

   Если секция подложена под уезжающую (преимущества), держит себя она сама
   и начинает ленту после паузы в длину уезда — поэтому инициализируется
   после сцен секций выше.

   Ниже 1024 и при reduced motion сцены нет — лента листается стрелками
   (components/results.js). */

export function initResultsRail() {
  const cleanups = [...document.querySelectorAll('[data-results]')].map(setup).filter(Boolean);

  if (!cleanups.length) return;

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(section) {
  const viewport = section.querySelector('[data-results-viewport]');
  const track = section.querySelector('[data-results-track]');

  if (!viewport || !track) return;

  /* Ход: лента шире видимой части — от левого края окна до правого поля
     секции. Считается по раскладке, без transform. */
  const distance = () => {
    const room =
      section.clientWidth - (parseFloat(getComputedStyle(section).paddingRight) || 0) - viewport.offsetLeft;

    return Math.max(0, track.scrollWidth - room);
  };

  return createRailScene({
    section,
    track,
    distance,
    exit: false,
    media: DESKTOP,
    hold: () => (section.dataset.underlap === 'on' ? exitLength() : 0)
  });
}
