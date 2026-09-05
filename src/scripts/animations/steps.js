import { gsap } from 'gsap';

import { createOverlayScene, crossfade, hideLayers } from './overlay-scene.js';
import { createRailScene } from './rail-scene.js';
import { DESKTOP, MOTION, TABLET } from '../utils/media.js';

/* Этапы на сцене «Наслоение» (overlay-scene.js): при прокрутке фото следующего
   этапа открывается снизу поверх текущего, номер в счётчике подменяется,
   а список подъезжает вверх: пройденный этап гаснет и уходит за верхний край,
   следующий встаёт на его место и набирает цвет.

   Фото, номера и этапы — три прохода по одному набору, модуль связывает
   их по индексу. Без сцены (и при отключённых анимациях) виден первый этап
   с фото и номером, остальные этапы стоят в списке приглушёнными.

   Ниже 1024 наслоения нет: слои собраны в карточки (steps.css), видны все,
   и ряд карточек едет лентой (rail-scene.js), пока секция закреплена.

   Описание сцен и их имена — в ANIMATIONS.md. */

export function initSteps() {
  const cleanups = [...document.querySelectorAll('[data-steps]')].map(setup).filter(Boolean);

  if (!cleanups.length) return;

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(section) {
  const list = section.querySelector('[data-steps-list]');
  const track = section.querySelector('[data-steps-track]');
  const items = [...section.querySelectorAll('[data-steps-item]')];
  const frames = [...section.querySelectorAll('[data-steps-frame]')];
  const counters = [...section.querySelectorAll('[data-steps-counter]')];
  const count = items.length;

  if (!list || count < 2 || frames.length !== count || counters.length !== count) return;

  const layers = [...frames, ...counters];
  const showAll = () => layers.forEach((layer) => {
    layer.hidden = false;
  });
  const showFirst = () => layers.forEach((layer, i) => {
    layer.hidden = i % count !== 0;
  });

  const mark = (index) => {
    items.forEach((item, i) => {
      if (i === index) {
        item.setAttribute('aria-current', 'step');
      } else {
        item.removeAttribute('aria-current');
      }
    });
  };

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add(`${MOTION} and ${DESKTOP}`, () => {
      section.dataset.scene = 'on';
      showAll();

      /* Приглушённость будущих этапов берётся из CSS и закрепляется инлайном,
         чтобы смена aria-current по ходу сцены не дёргала прозрачность. */
      const dim = parseFloat(getComputedStyle(items[1]).opacity);

      gsap.set(items, { opacity: (index) => (index ? dim : 1) });
      hideLayers(counters.slice(1));

      /* Ход списка — до верха следующего этапа; считается на каждом refresh. */
      const travel = (index) => -(items[index].offsetTop - items[0].offsetTop);

      createOverlayScene({
        section,
        frames,
        build: (timeline, i, at, step) => {
          crossfade(timeline, counters[i - 1], counters[i], at, step);
          timeline
            .to(list, { y: () => travel(i), duration: step }, at)
            .to(items[i - 1], { autoAlpha: 0, duration: step * 0.4 }, at)
            .to(items[i], { opacity: 1, duration: step * 0.5 }, at + step * 0.5);
        },
        onIndex: mark
      });

      return () => {
        delete section.dataset.scene;
        mark(0);
        showFirst();
      };
    });

    /* Планшет: все этапы видны карточками в ряд, в том числе без анимаций. */
    mm.add(TABLET, () => {
      showAll();

      return showFirst;
    });
  }, section);

  const rail = createRailScene({ section, track, media: TABLET });

  return () => {
    rail?.();
    context.revert();
  };
}
