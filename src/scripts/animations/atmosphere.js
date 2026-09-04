import { gsap } from 'gsap';

import { exitLength, screen } from './timing.js';

/* Сцена секции: сначала пауза, пока предыдущая секция уезжает влево,
   затем шапка уходит вверх, а маленькое превью разрастается в большой кадр.
   Рост сделан через clip-path (окно меняет пропорции) и масштаб самой
   картинки — так кадр не растягивается и не дёргается layout. */
export function initAtmosphere() {
  const section = document.querySelector('[data-section="atmosphere"]');
  const head = section?.querySelector('[data-atmosphere-head]');
  const media = section?.querySelector('[data-atmosphere-media]');
  const preview = section?.querySelector('[data-atmosphere-preview]');
  const scene = section?.querySelector('[data-atmosphere-scene]');
  const image = section?.querySelector('[data-atmosphere-scene-image]');
  const features = section?.querySelector('[data-atmosphere-features]');

  if (!section || !head || !media || !preview || !scene || !image || !features) return;

  /* Пауза равна длине уезда предыдущей секции — берём её оттуда же, иначе
     фазы разъедутся при смене темпа. */
  const hold = () => exitLength();
  const morph = () => screen() * 1.1;

  const radius = () => parseFloat(getComputedStyle(scene).borderTopLeftRadius) || 0;
  const closed = () =>
    `inset(0px ${scene.offsetWidth - preview.offsetWidth}px` +
    ` ${scene.offsetHeight - preview.offsetHeight}px 0px round ${radius()}px)`;
  const opened = () => `inset(0px 0px 0px 0px round ${radius()}px)`;

  /* Шапка вынута из потока, поэтому медиа стартует смещённым вниз на её высоту
     с отступом и приезжает в ноль. Так высота секции сразу равна финальной,
     и под кадром остаётся ровно её нижний отступ, а не пустота. */
  const shift = () =>
    head.offsetHeight + parseFloat(getComputedStyle(section).rowGap || 0);

  /* Стартовый масштаб кадра — такой, чтобы картинка ровно закрывала окно
     превью по высоте, и сдвиг, который ставит это окно по центру кадра:
     иначе в маленьком виде показывался бы левый край, а не середина. */
  const startScale = () => preview.offsetHeight / scene.offsetHeight;
  const startShift = () => -(scene.offsetWidth * startScale() - preview.offsetWidth) / 2;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      section.dataset.morph = 'on';

      const pause = hold();
      const length = morph();

      const timeline = gsap.timeline({
        defaults: { ease: 'none' },
        scrollTrigger: {
          trigger: section,
          start: 'top top',
          end: () => `+=${hold() + morph()}`,
          pin: true,
          scrub: true,
          invalidateOnRefresh: true
        }
      });

      timeline
        .to(head, { yPercent: -100, opacity: 0, duration: length * 0.35 }, pause)
        .fromTo(
          media,
          { y: () => shift() },
          { y: 0, duration: length * 0.35, immediateRender: true },
          pause
        )
        .fromTo(
          scene,
          { clipPath: () => closed() },
          { clipPath: () => opened(), duration: length * 0.45 },
          pause + length * 0.3
        )
        .fromTo(
          image,
          { scale: () => startScale(), x: () => startShift(), transformOrigin: 'top left' },
          { scale: 1, x: 0, duration: length * 0.45 },
          pause + length * 0.3
        )
        .to(features, { opacity: 1, duration: length * 0.25 }, pause + length * 0.75);

      return () => delete section.dataset.morph;
    });
  }, section);

  return () => context.revert();
}
