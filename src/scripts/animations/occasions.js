import { createRailScene } from './rail-scene.js';
import { TABLET } from '../utils/media.js';

/* «Где удобно» на странице выездных услуг. На десктопе это сетка без сцены.
   Ниже 1024 карточки стоят в ряд (occasions.css) и едут лентой
   (rail-scene.js), пока секция закреплена, — как услуги с выездом и этапы.

   Описание сцен и их имена — в ANIMATIONS.md. */
export function initOccasions() {
  const cleanups = [...document.querySelectorAll('[data-occasions]')]
    .map((section) =>
      createRailScene({
        section,
        track: section.querySelector('[data-occasions-track]'),
        media: TABLET
      })
    )
    .filter(Boolean);

  if (!cleanups.length) return;

  return () => cleanups.forEach((cleanup) => cleanup());
}
