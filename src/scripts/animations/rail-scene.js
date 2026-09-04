import { createPinnedScene } from './pinned-scene.js';
import { RAIL_PACE } from './timing.js';

/* Лента (rail): секция закреплена, содержимое едет вбок на «ширина ленты −
   ширина экрана». Вместе с лентой можно двигать что-то ещё (travellers).
   Стыки, жалюзи и уезд — в общем каркасе pinned-scene.js.

   Ход ленты и прокрутка, которую он стоит, — разные величины: лента едет
   на всю свою ширину, а прокрутки на это тратится меньше (RAIL_PACE).

   Описание сцен и их имена — в ANIMATIONS.md. */
export function createRailScene({ section, track, travellers = [], ...scene }) {
  if (!section || !track) return;

  const distance = () => Math.max(0, track.offsetWidth - window.innerWidth);
  const railLength = () => distance() * RAIL_PACE;

  return createPinnedScene({
    section,
    ...scene,
    phase: {
      length: railLength,
      build: (timeline, at) =>
        timeline.to([track, ...travellers], { x: () => -distance(), duration: railLength() }, at)
    }
  });
}
