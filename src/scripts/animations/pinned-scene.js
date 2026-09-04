import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { blindsOpen } from './blinds.js';
import { exitLength, openLength, screen } from './timing.js';
import { nextSection } from '../utils/siblings.js';

/* Каркас закреплённой сцены с уездом: пауза (hold) на длину фазы предыдущей
   секции → жалюзи (blinds, необязательно) → своя фаза (phase) → уезд влево,
   открывающий следующую секцию, заранее подложенную под текущую (underlap).

   Своя фаза приходит снаружи: `phase.length()` — сколько прокрутки она стоит,
   `phase.build(timeline, at)` — какие твины поставить на таймлайн с позиции `at`.
   Так лента (rail-scene.js) и стопка (stack-scene.js) делят один каркас
   и не расходятся в устройстве стыков.

   Длительности фаз заданы в пикселях прокрутки, а не долями: подкладка
   следующей секции считается от длины уезда, и при пропорциях они разъезжаются.

   Описание сцен и их имена — в ANIMATIONS.md. */
export function createPinnedScene({
  section,
  phase,
  blinds = null,
  holdNext = true,
  hold = () => 0
}) {
  const next = nextSection(section);

  if (!section || !next || !phase) return;

  const hasBlinds = Boolean(blinds?.length);
  const opening = () => (hasBlinds ? openLength() : 0);

  const applyUnderlap = () =>
    next.style.setProperty('--underlap', `${screen() + exitLength()}px`);

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      section.dataset.scene = 'on';
      next.dataset.underlap = 'on';
      applyUnderlap();

      const timeline = gsap.timeline({
        defaults: { ease: 'none' },
        scrollTrigger: {
          trigger: section,
          start: 'top top',
          end: () => `+=${hold() + opening() + phase.length() + exitLength()}`,
          pin: true,
          scrub: true,
          invalidateOnRefresh: true,
          onRefreshInit: applyUnderlap
        }
      });

      const pause = hold();
      const own = pause + opening();

      if (hasBlinds) {
        timeline.to(blinds, blindsOpen(opening()), pause);
      }

      phase.build(timeline, own);

      timeline.to(section, { xPercent: -100, duration: exitLength() }, own + phase.length());

      /* Пока секция уезжает, следующая должна стоять неподвижно. Если у неё есть
         своя закреплённая сцена, держать себя должна она сама — два пина на одном
         элементе конфликтуют; тогда сюда передаётся holdNext: false. */
      if (holdNext) {
        ScrollTrigger.create({
          trigger: next,
          start: 'top top',
          end: () => `+=${exitLength()}`,
          pin: true,
          invalidateOnRefresh: true
        });
      }

      return () => {
        delete section.dataset.scene;
        delete next.dataset.underlap;
        next.style.removeProperty('--underlap');
      };
    });
  }, section);

  return () => context.revert();
}
