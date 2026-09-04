import { createPinnedScene } from './pinned-scene.js';
import { stackStep } from './timing.js';

/* Стопка (stack): карточки приезжают справа по одной и встают в один слот.
   Пока новая едет, предыдущая оседает — чуть уменьшается и сдвигается влево —
   и уходит под неё. Все осевшие лежат в одном месте, каждая новая поверх
   старой: порядок задаёт разметка, отдельных z-index не нужно.

   Въезд крутит обёртку (item), оседание — саму карточку (card). Это разные
   элементы, поэтому два движения по одной оси не спорят за один transform.
   Стыки, жалюзи и уезд — в общем каркасе pinned-scene.js.

   Описание сцен и их имена — в ANIMATIONS.md. */

/* Из макета: активная карточка 700 в ширину, осевшая — 620, и её центр
   смещён влево на 340. Держим долями от ширины карточки, чтобы считалось
   от её реального размера. */
const SETTLE_SCALE = 620 / 700;
const SETTLE_SHIFT = -340 / 700;

export function createStackScene({ section, track, items, cards, ...scene }) {
  if (!section || !track || !items?.length || items.length !== cards?.length) return;

  const steps = items.length - 1;
  const length = () => steps * stackStep();

  /* Карточка стартует за правым краем экрана: слот стоит на offsetLeft
     от левого края секции, а секция в закреплённом виде — во всю ширину. */
  const enter = () => window.innerWidth - track.offsetLeft;
  const shift = () => track.offsetWidth * SETTLE_SHIFT;

  return createPinnedScene({
    section,
    ...scene,
    phase: {
      length,
      build: (timeline, at) => {
        const step = stackStep();

        items.forEach((item, index) => {
          if (index > 0) {
            timeline.fromTo(
              item,
              { x: () => enter() },
              { x: 0, duration: step, immediateRender: true },
              at + (index - 1) * step
            );
          }

          if (index < steps) {
            timeline.fromTo(
              cards[index],
              { x: 0, scale: 1 },
              { x: () => shift(), scale: SETTLE_SCALE, duration: step, immediateRender: false },
              at + index * step
            );
          }
        });
      }
    }
  });
}
