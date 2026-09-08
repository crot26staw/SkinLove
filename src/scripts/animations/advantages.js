import { gsap } from 'gsap';

import { createRailScene } from './rail-scene.js';
import { nextSection } from '../utils/siblings.js';

/* Преимущества: лента → уезд, без жалюзи — секция приходит обычной прокруткой.
   Следующую секцию на время уезда держим отсюда, если у неё нет своей сцены.
   Отзывы с жалюзи (страницы услуг) держат себя сами — см. reviews.js;
   отзывы без жалюзи (главная) своей сцены не имеют. СТА, если стоит сразу
   за лентой, держит себя своей сборкой — см. cta.js.

   Карточки, которые въезжают из-за правого края, стоят ниже общей линии
   и поднимаются к ней, пока показываются из-за края: подъём начинается,
   когда в экран входит левый край карточки, и заканчивается, когда она
   показалась на SETTLE_AT своей ширины. Карточки, видимые с самого начала,
   стоят на линии сразу. Опускается тело карточки, а не она вся: разделитель
   слева от неё остаётся на месте.

   Ниже 768 после ленты секция поджимается снизу на --shrink из CSS (каркас
   pinned-scene.js), а лента за это время поднимается на половину: линия фото
   с середины экрана переходит на середину укороченной секции, и текст
   последних карточек не уходит под срез. */

/* На сколько опущена въезжающая карточка — в долях ширины её тела (100 из 754). */
const DROP = 100 / 754;

/* На какой доле своей ширины показавшаяся карточка встаёт на линию. */
const SETTLE_AT = 0.6;

export function initAdvantages() {
  const section = document.querySelector('[data-section="advantages"]');

  if (!section) return;

  const next = nextSection(section);
  const track = section.querySelector('[data-advantages-track]');
  const bodies = [...section.querySelectorAll('[data-advantage-body]')];
  const shrink = () => parseFloat(getComputedStyle(section).getPropertyValue('--shrink')) || 0;

  return createRailScene({
    section,
    track,
    holdNext: !next?.querySelector('[data-blind], [data-cta-card]'),
    shrink,
    build: (timeline, at, { length, distance }) => {
      const width = window.innerWidth;
      const origin = track.getBoundingClientRect().left;

      /* Момент хода, когда точка ленты доезжает до отметки экрана:
         лента едет на distance за length прокрутки, равномерно. */
      const when = (point, mark) => ((point - mark) / distance) * length;

      bodies.forEach((body) => {
        const rect = body.getBoundingClientRect();
        const left = rect.left - origin;
        const shown = left + rect.width * SETTLE_AT;

        if (shown <= width) return;

        const enter = Math.max(0, when(left, width));
        const settle = Math.min(length, when(shown, width));

        timeline.fromTo(
          body,
          { y: () => body.offsetWidth * DROP },
          { y: 0, duration: settle - enter, immediateRender: true },
          at + enter
        );
      });

      if (shrink()) {
        timeline.to(track, { y: () => -shrink() / 2, duration: shrink() }, at + length);
      }
    }
  });
}
