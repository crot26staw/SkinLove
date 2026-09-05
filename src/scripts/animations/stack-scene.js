import { createPinnedScene } from './pinned-scene.js';
import { stackStep } from './timing.js';

/* Стопка (stack): карточки лежат в ряд от слота вправо с зазором из CSS,
   следующая с самого начала выглядывает из-за правого края экрана. На каждом
   шаге ряд сдвигается на карточку влево: следующая встаёт в слот, а та, что
   его занимала, оседает — чуть уменьшается на месте и уходит под новую.
   Все осевшие лежат в одном месте, по центру, каждая новая поверх старой:
   порядок задаёт разметка, отдельных z-index не нужно.

   Ряд едет равномерно, поэтому у каждой обёртки один линейный твин: из своего
   места в ряду в слот за столько шагов, сколько карточек перед ней. Въезд
   крутит обёртку (item), оседание — саму карточку (card). Это разные
   элементы, поэтому два движения по одной оси не спорят за один transform.
   Стыки, жалюзи и уезд — в общем каркасе pinned-scene.js.

   Режим стопки в CSS (лента сжата до слота, карточки друг под другом)
   включает атрибут data-stack: у той же секции на другой ширине может
   играть другая фаза, и общий data-scene для этого не годится.

   Описание сцен и их имена — в ANIMATIONS.md. */

/* Из макета: активная карточка 700 в ширину, осевшая — 620, центр на месте. */
const SETTLE_SCALE = 620 / 700;

export function createStackScene({ section, track, items, cards, ...scene }) {
  if (!section || !track || !items?.length || items.length !== cards?.length) return;

  const steps = items.length - 1;
  const length = () => steps * stackStep();

  /* Место карточки в ряду: слот плюс столько шагов ряда, сколько карточек
     перед ней. Шаг — ширина слота и зазор ряда из CSS. */
  const gap = () => parseFloat(getComputedStyle(track).columnGap) || 0;
  const enter = (index) => index * (track.offsetWidth + gap());

  return createPinnedScene({
    section,
    ...scene,
    phase: {
      length,
      build: (timeline, at) => {
        const step = stackStep();

        /* До первого замера: стартовые положения считаются от ширины слота. */
        section.dataset.stack = 'on';

        items.forEach((item, index) => {
          if (index > 0) {
            timeline.fromTo(
              item,
              { x: () => enter(index) },
              { x: 0, duration: index * step, immediateRender: true },
              at
            );
          }

          if (index < steps) {
            timeline.fromTo(
              cards[index],
              { scale: 1 },
              { scale: SETTLE_SCALE, duration: step, immediateRender: false },
              at + index * step
            );
          }
        });

        return () => delete section.dataset.stack;
      }
    }
  });
}
