import { createPinnedScene } from './pinned-scene.js';
import { stackStep } from './timing.js';

/* Стопка (stack): карточки лежат в ряд от слота вправо с зазором из CSS,
   следующая с самого начала выглядывает из-за правого края экрана. На каждом
   шаге ряд сдвигается на карточку влево: следующая встаёт в слот, а та, что
   его занимала, оседает — чуть уменьшается, сдвигается влево и уходит под
   новую. Все осевшие лежат в одном месте, каждая новая поверх старой:
   порядок задаёт разметка, отдельных z-index не нужно.

   Ряд едет равномерно, поэтому у каждой обёртки один линейный твин: из своего
   места в ряду в слот за столько шагов, сколько карточек перед ней. Въезд
   крутит обёртку (item), оседание — саму карточку (card). Это разные
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

  /* Место карточки в ряду: слот плюс столько шагов ряда, сколько карточек
     перед ней. Шаг — ширина слота и зазор ряда из CSS. */
  const gap = () => parseFloat(getComputedStyle(track).columnGap) || 0;
  const enter = (index) => index * (track.offsetWidth + gap());
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
              { x: () => enter(index) },
              { x: 0, duration: index * step, immediateRender: true },
              at
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
