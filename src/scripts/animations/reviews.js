import { gsap } from 'gsap';

import { blindsOpen } from './blinds.js';
import { blindsExitLength, exitLength, openLength } from './timing.js';
import { previousSection } from '../utils/siblings.js';
import { paintSpacer } from '../utils/pin-spacer.js';
import { MOTION } from '../utils/media.js';

/* Отзывы с жалюзи на страницах услуг. Два случая, различаются по подкладке:

   1. Отзывы подложены под уезжающую секцию (data-underlap). Своей фазы у них
      нет, но держать себя на время уезда они должны сами — два пина на одном
      элементе конфликтуют, поэтому предыдущая сцена, увидев у отзывов жалюзи,
      удержание не ставит (holdNext: false). Полосы расходятся вместе с уездом:
      начинают с его началом и полностью открыты, когда от предыдущей секции
      на экране остаётся BLINDS_TAIL, — переход идёт без остановки.

   2. Предыдущая секция не уезжает, а прокручивается обычным потоком (wellness:
      результаты). Отзывы входят в кадр снизу, и держать их не нужно: полосы
      расходятся на въезде, как у направлений, — начинают, когда предыдущая
      секция ушла вверх на ENTRY_AT, и полностью открыты к моменту, когда отзывы
      встали в верх экрана.

   Отзывы без жалюзи (главная) сюда не попадают: их держит предыдущая сцена.
   Ниже 1024 уездов и подкладок нет, и отзывы всегда раскрываются на въезде.

   Описание сцен и их имена — в ANIMATIONS.md. */

/* На сколько пикселей предыдущая секция уходит за верх экрана до начала раскрытия. */
const ENTRY_AT = 50;

export function initReviewsBlinds() {
  const cleanups = [...document.querySelectorAll('[data-section="reviews"]')]
    .map(setup)
    .filter(Boolean);

  if (!cleanups.length) return;

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(section) {
  const bars = [...section.querySelectorAll('[data-blind]')];
  const previous = previousSection(section);

  if (!bars.length || !previous) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add(MOTION, () => {
      section.dataset.scene = 'on';
      section.style.setProperty('--blinds-color', getComputedStyle(previous).backgroundColor);

      /* Подкладку ставит сцена предыдущей секции — она инициализируется раньше. */
      if (section.dataset.underlap === 'on') {
        holdAndOpen(section, bars);
      } else {
        openOnEntry(section, previous, bars);
      }

      return () => {
        delete section.dataset.scene;
        section.style.removeProperty('--blinds-color');
      };
    });
  }, section);

  return () => context.revert();
}

/* Секция стоит под уезжающей: держит себя на весь уезд и раскрывается
   вместе с ним. */
function holdAndOpen(section, bars) {
  gsap
    .timeline({
      defaults: { ease: 'none' },
      scrollTrigger: {
        trigger: section,
        start: 'top top',
        end: () => `+=${exitLength()}`,
        pin: true,
        scrub: true,
        invalidateOnRefresh: true,
        /* Секция ниже экрана: распорка под ней красится в её цвет, иначе
           на стыке просвечивает фон страницы. На refresh, а не сразу:
           в WebKit при создании распорки ещё нет. */
        onRefresh: paintSpacer
      }
    })
    .to(bars, blindsOpen(blindsExitLength()), 0);
}

/* Секция входит в кадр обычной прокруткой: раскрывается, пока поднимается.
   Отсчёт — от предыдущей секции: если та закреплена сценой выше, ScrollTrigger
   сам сдвинет отметку на длину её пина. Длительность условная, scrub растянет
   её на диапазон триггера. */
function openOnEntry(section, previous, bars) {
  gsap.to(bars, {
    ...blindsOpen(openLength()),
    ease: 'none',
    scrollTrigger: {
      trigger: previous,
      start: `top top-=${ENTRY_AT}`,
      endTrigger: section,
      end: 'top top',
      scrub: true,
      invalidateOnRefresh: true
    }
  });
}
