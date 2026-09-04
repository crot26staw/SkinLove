import { createStackScene } from './stack-scene.js';
import { dissolveLength } from './timing.js';

/* Направления: пауза → жалюзи → стопка → уезд.
   Пауза равна растворению «о клинике»: секция подложена под неё и уже стоит
   на месте, поэтому её собственный пин начинается одновременно с растворением,
   а ждать нужно до его конца — иначе жалюзи разъезжаются под ещё видимой
   секцией. Следующая секция («атмосфера») держит себя сама — её сцена
   начинается с паузы той же длины, что и уезд. */
export function initServices() {
  const section = document.querySelector('[data-section="services"]');

  if (!section) return;

  const items = [...section.querySelectorAll('[data-services-item]')];

  return createStackScene({
    section,
    track: section.querySelector('[data-services-track]'),
    items,
    cards: items.map((item) => item.querySelector('[data-services-card]')),
    blinds: section.querySelectorAll('[data-blind]'),
    hold: dissolveLength,
    holdNext: false
  });
}
