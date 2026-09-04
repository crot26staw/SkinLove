import { gsap } from 'gsap';

import { overlayStep } from './timing.js';

/* Каркас наслоения (overlay): секция закреплена, кадры лежат стопкой, и при
   прокрутке каждый следующий открывается снизу поверх текущего — окно
   раскрывается через clip-path, а картинка внутри подъезжает снизу. Что ещё
   меняется на шаге, секция добавляет сама через `build(timeline, index, at,
   step)`; текст подменяется общей парой hideLayers() / crossfade(), чтобы
   услуги и этапы гасли и проявлялись одинаково.

   Длительности — в пикселях прокрутки, как у остальных закреплённых сцен.
   Вызывается внутри matchMedia: стартовые состояния ставит здесь, и при
   отключённых анимациях их нет.

   Описание сцен и их имена — в ANIMATIONS.md. */

/* Насколько картинка подъезжает снизу вместе с раскрытием, в долях высоты. */
const RISE = 12;

/* Ход текста при подмене, в px. */
const SHIFT = 20;

/* Доли шага: уход текущего текста и момент появления следующего. */
const FADE_OUT = 0.4;
const FADE_IN_AT = 0.5;

export function createOverlayScene({ section, frames, build = () => {}, onIndex = () => {} }) {
  const count = frames.length;
  const rest = frames.slice(1);
  const visuals = rest.map((frame) => frame.firstElementChild).filter(Boolean);

  gsap.set(rest, { clipPath: 'inset(100% 0% 0% 0%)' });
  gsap.set(visuals, { yPercent: RISE });

  const step = overlayStep();

  const timeline = gsap.timeline({
    defaults: { ease: 'none' },
    scrollTrigger: {
      trigger: section,
      start: 'top top',
      end: () => `+=${overlayStep() * (count - 1)}`,
      pin: true,
      scrub: true,
      invalidateOnRefresh: true,
      onUpdate: (self) => onIndex(Math.round(self.progress * (count - 1)))
    }
  });

  for (let i = 1; i < count; i++) {
    const at = step * (i - 1);

    timeline.to(frames[i], { clipPath: 'inset(0% 0% 0% 0%)', duration: step }, at);

    if (frames[i].firstElementChild) {
      timeline.to(frames[i].firstElementChild, { yPercent: 0, duration: step }, at);
    }

    build(timeline, i, at, step);
  }

  return {
    position: (index) => timeline.scrollTrigger.start + overlayStep() * index
  };
}

/* Стартовое состояние слоёв, которые проявятся позже. autoAlpha, а не opacity:
   слои лежат стопкой в одной ячейке, и прозрачный слой поверх активного
   перехватывал бы курсор у его кнопок. */
export function hideLayers(layers) {
  gsap.set(layers, { autoAlpha: 0, y: SHIFT });
}

/* Подмена: текущие слои гаснут с ходом вверх, следующие проявляются снизу. */
export function crossfade(timeline, from, to, at, step) {
  timeline
    .to(from, { autoAlpha: 0, y: -SHIFT, duration: step * FADE_OUT }, at)
    .to(to, { autoAlpha: 1, y: 0, duration: step * (1 - FADE_IN_AT) }, at + step * FADE_IN_AT);
}
