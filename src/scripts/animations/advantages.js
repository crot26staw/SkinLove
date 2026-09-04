import { createRailScene } from './rail-scene.js';
import { nextSection } from '../utils/siblings.js';

/* Преимущества: лента → уезд, без жалюзи — секция приходит обычной прокруткой.
   Следующую секцию на время уезда держим отсюда, если у неё нет своей сцены.
   Отзывы с жалюзи (страницы услуг) держат себя сами — см. reviews.js;
   отзывы без жалюзи (главная) своей сцены не имеют. */
export function initAdvantages() {
  const section = document.querySelector('[data-section="advantages"]');

  if (!section) return;

  const next = nextSection(section);

  return createRailScene({
    section,
    track: section.querySelector('[data-advantages-track]'),
    holdNext: !next?.querySelector('[data-blind]')
  });
}
