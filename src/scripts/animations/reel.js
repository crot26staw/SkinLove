import { gsap } from 'gsap';

import { crossfade, hideLayers } from './overlay-scene.js';
import { exitLength, screen } from './timing.js';
import { paintSpacer } from '../utils/pin-spacer.js';
import { createVideoScrub } from '../utils/video-scrub.js';
import { DESKTOP, MOTION } from '../utils/media.js';

/* Плёнка (reel): секция закреплена, видео в ней (data-reel-video) идёт от
   прокрутки, а текст подменяется по ходу ролика. Слои текста помечены
   data-reel-layer с номером: слои с одним номером (заголовок и описание)
   показываются вместе, смена идёт через равные доли ролика — та же пара
   hideLayers() / crossfade(), что в «Наслоении».

   На десктопе секция подложена под уезжающие преимущества, поэтому сцена
   начинается с паузы на длину уезда, как у «Атмосферы»: держит секцию
   сама, каркас предыдущей её не закрепляет (holdNext в advantages.js).

   Описание сцен и их имена — в ANIMATIONS.md. */

/* Длина ролика на прокрутке — в экранах. */
const REEL_SHARE = 1.6;

/* Доля шага, которую занимает подмена текста; стоит по центру шага. */
const SWAP_SHARE = 0.4;

export function initReel() {
  const contexts = [...document.querySelectorAll('[data-reel]')]
    .map(createReel)
    .filter(Boolean);

  if (!contexts.length) return;

  return () => contexts.forEach((context) => context.revert());
}

function createReel(section) {
  const card = section.querySelector('[data-reel-card]');
  const video = section.querySelector('[data-reel-video]');

  if (!card || !video) return;

  const groups = [];
  section.querySelectorAll('[data-reel-layer]').forEach((layer) => {
    const index = Number(layer.dataset.reelLayer) || 0;
    (groups[index] ??= []).push(layer);
  });
  const steps = groups.filter(Boolean);

  return gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add({ motion: MOTION, desktop: DESKTOP }, (ctx) => {
      const { motion, desktop } = ctx.conditions;

      if (!motion) return;

      const scrub = createVideoScrub(video);
      const hold = () => (desktop ? exitLength() : 0);
      const length = () => screen() * REEL_SHARE;
      const rest = steps.slice(1).flat();

      rest.forEach((layer) => (layer.hidden = false));
      hideLayers(rest);

      const pause = hold();
      const reel = length();

      const timeline = gsap.timeline({
        defaults: { ease: 'none' },
        scrollTrigger: {
          /* Закрепляется по центру карточки: на десктопе она в экран, и это
             «верх в верх», ниже 1024 карточка встаёт посередине экрана,
             а не под шапкой. */
          trigger: card,
          start: 'center center',
          pin: section,
          end: () => `+=${hold() + length()}`,
          scrub: true,
          invalidateOnRefresh: true,
          /* Ниже 1024 под секцией ничего не подложено, и распорку можно красить:
             секция бывает ниже экрана, под ней просвечивал бы первый экран. */
          onRefresh: desktop ? undefined : paintSpacer,
          onUpdate: ({ progress, end, start }) => {
            const own = (progress * (end - start) - hold()) / length();
            scrub.seek(gsap.utils.clamp(0, 1, own));
          }
        }
      });

      /* Ролик делится на равные шаги, подмена — по центру границы шагов. */
      const step = reel / steps.length;
      const swap = step * SWAP_SHARE;

      for (let i = 1; i < steps.length; i++) {
        crossfade(timeline, steps[i - 1], steps[i], pause + step * i - swap / 2, swap);
      }

      /* Таймлайн длиной во всю сцену, иначе scrub сожмёт его в её начало. */
      timeline.set({}, {}, pause + reel);

      return () => {
        scrub.destroy();
        rest.forEach((layer) => (layer.hidden = true));
      };
    });
  }, section);
}
