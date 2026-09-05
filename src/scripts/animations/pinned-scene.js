import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { blindsOpen } from './blinds.js';
import { exitLength, openLength, screen } from './timing.js';
import { nextSection } from '../utils/siblings.js';
import { paintSpacer } from '../utils/pin-spacer.js';
import { DESKTOP, MOTION } from '../utils/media.js';

/* Каркас закреплённой сцены: пауза (hold) на длину фазы предыдущей секции →
   жалюзи (blinds, необязательно) → своя фаза (phase) → на десктопе уезд
   влево, открывающий следующую секцию, заранее подложенную под текущую
   (underlap). Ниже 1024 стыков нет: секция играет свою фазу и снимается
   с закрепления, следующая идёт обычной прокруткой.

   Своя фаза приходит снаружи: `phase.length()` — сколько прокрутки она стоит,
   `phase.build(timeline, at)` — какие твины поставить на таймлайн с позиции
   `at`; вернуть может функцию очистки. Так лента (rail-scene.js) и стопка
   (stack-scene.js) делят один каркас и не расходятся в устройстве стыков.
   `media` ограничивает сцену своим диапазоном, когда у секции на разных
   ширинах разные фазы.

   Длительности фаз заданы в пикселях прокрутки, а не долями: подкладка
   следующей секции считается от длины уезда, и при пропорциях они разъезжаются.

   Описание сцен и их имена — в ANIMATIONS.md. */
export function createPinnedScene({
  section,
  phase,
  blinds = null,
  holdNext = true,
  hold = () => 0,
  media = 'all'
}) {
  const next = nextSection(section);

  if (!section || !next || !phase) return;

  const hasBlinds = Boolean(blinds?.length);
  const opening = () => (hasBlinds ? openLength() : 0);

  const applyUnderlap = () =>
    next.style.setProperty('--underlap', `${screen() + exitLength()}px`);

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add({ motion: MOTION, desktop: DESKTOP, scope: media }, (ctx) => {
      const { motion, desktop, scope } = ctx.conditions;

      if (!motion || !scope) return;

      const exit = desktop;

      section.dataset.scene = 'on';

      if (exit) {
        next.dataset.underlap = 'on';
        applyUnderlap();
      }

      const timeline = gsap.timeline({
        defaults: { ease: 'none' },
        scrollTrigger: {
          trigger: section,
          start: 'top top',
          end: () => `+=${hold() + opening() + phase.length() + (exit ? exitLength() : 0)}`,
          pin: true,
          scrub: true,
          invalidateOnRefresh: true,
          onRefreshInit: exit ? applyUnderlap : undefined
        }
      });

      const pause = hold();
      const own = pause + opening();

      if (hasBlinds) {
        timeline.to(blinds, blindsOpen(opening()), pause);
      }

      const teardown = phase.build(timeline, own);

      if (exit) {
        timeline.to(section, { xPercent: -100, duration: exitLength() }, own + phase.length());

        /* Пока секция уезжает, следующая должна стоять неподвижно. Если у неё
           есть своя закреплённая сцена, держать себя должна она сама — два пина
           на одном элементе конфликтуют; тогда сюда передаётся holdNext: false.
           Распорка удержания красится в цвет секции: она ниже экрана, и под ней
           просвечивал бы фон страницы. */
        if (holdNext) {
          paintSpacer(
            ScrollTrigger.create({
              trigger: next,
              start: 'top top',
              end: () => `+=${exitLength()}`,
              pin: true,
              invalidateOnRefresh: true
            })
          );
        }
      }

      return () => {
        teardown?.();
        delete section.dataset.scene;

        if (exit) {
          delete next.dataset.underlap;
          next.style.removeProperty('--underlap');
        }
      };
    });
  }, section);

  return () => context.revert();
}
