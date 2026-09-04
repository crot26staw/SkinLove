import { createRailScene } from './rail-scene.js';

/* Преимущества: лента → уезд, без жалюзи — секция приходит обычной прокруткой.
   Отзывы своей сцены пока не имеют, поэтому держим их отсюда. */
export function initAdvantages() {
  const section = document.querySelector('[data-section="advantages"]');

  if (!section) return;

  return createRailScene({
    section,
    track: section.querySelector('[data-advantages-track]')
  });
}
