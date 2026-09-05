import { gsap } from 'gsap';

import { exitLength, screen } from './timing.js';
import { DESKTOP, MOTION } from '../utils/media.js';

/* Сцена секции: сначала пауза, пока предыдущая секция уезжает влево,
   затем шапка уходит вверх, а маленькое превью разрастается в большой кадр.
   Рост сделан через clip-path (окно меняет пропорции) и масштаб самой
   картинки — так кадр не растягивается и не дёргается layout.

   Рост начинается почти сразу после открытия секции — через GROW_LEAD
   пикселей прокрутки, — пока шапка ещё уходит; следом за ростом проявляются
   подписи. Длина сцены складывается из этих отрезков.

   Ниже 1024 сцена та же, но без паузы: уезда у предыдущей секции там нет.
   Окно превью в макете 768 стоит по центру, поэтому кадр раскрывается
   из того места, где стоит превью, а не из левого края. */

/* Через сколько пикселей прокрутки после открытия секции кадр начинает расти. */
const GROW_LEAD = 50;

/* Длины отрезков в долях экрана: уход шапки, рост кадра, появление подписей. */
const HEAD_SHARE = 0.385;
const GROW_SHARE = 0.5;
const FEATURES_SHARE = 0.275;
export function initAtmosphere() {
  const section = document.querySelector('[data-section="atmosphere"]');
  const head = section?.querySelector('[data-atmosphere-head]');
  const media = section?.querySelector('[data-atmosphere-media]');
  const preview = section?.querySelector('[data-atmosphere-preview]');
  const scene = section?.querySelector('[data-atmosphere-scene]');
  const image = section?.querySelector('[data-atmosphere-scene-image]');
  const features = section?.querySelector('[data-atmosphere-features]');

  if (!section || !head || !media || !preview || !scene || !image || !features) return;

  const grow = () => screen() * GROW_SHARE;
  const morph = () => GROW_LEAD + screen() * (GROW_SHARE + FEATURES_SHARE);

  /* Окно превью — там, где превью стоит в потоке: слева на десктопе,
     по центру на планшете. Превью в режиме сцены вынуто из потока, но своё
     статическое место в медиа сохраняет. */
  const radius = () => parseFloat(getComputedStyle(scene).borderTopLeftRadius) || 0;
  const previewLeft = () => preview.offsetLeft - scene.offsetLeft;
  const closed = () =>
    `inset(0px ${scene.offsetWidth - previewLeft() - preview.offsetWidth}px` +
    ` ${scene.offsetHeight - preview.offsetHeight}px ${previewLeft()}px round ${radius()}px)`;
  const opened = () => `inset(0px 0px 0px 0px round ${radius()}px)`;

  /* Шапка вынута из потока, поэтому медиа стартует смещённым вниз на её высоту
     с отступом и приезжает в ноль. Так высота секции сразу равна финальной,
     и под кадром остаётся ровно её нижний отступ, а не пустота. */
  const shift = () =>
    head.offsetHeight + parseFloat(getComputedStyle(section).rowGap || 0);

  /* Стартовый масштаб кадра — такой, чтобы картинка ровно закрывала окно
     превью: по высоте, а если превью во всю ширину кадра (мобильный) — по
     ширине, иначе картинка была бы уже окна. Сдвиг ставит окно по центру
     кадра: иначе в маленьком виде показывался бы левый край, а не середина. */
  const startScale = () =>
    Math.max(preview.offsetHeight / scene.offsetHeight, preview.offsetWidth / scene.offsetWidth);
  const startShift = () =>
    previewLeft() - (scene.offsetWidth * startScale() - preview.offsetWidth) / 2;

  /* По вертикали то же: если картинка выше окна (превью во всю ширину),
     окно смотрит в её середину, а не на верхний край. */
  const startLift = () => -(scene.offsetHeight * startScale() - preview.offsetHeight) / 2;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add({ motion: MOTION, desktop: DESKTOP }, (ctx) => {
      const { motion, desktop } = ctx.conditions;

      if (!motion) return;

      /* Пауза равна длине уезда предыдущей секции — берём её оттуда же, иначе
         фазы разъедутся при смене темпа. Ниже 1024 уезда нет, и паузы тоже. */
      const hold = () => (desktop ? exitLength() : 0);

      section.dataset.morph = 'on';

      const pause = hold();
      const start = pause + GROW_LEAD;
      const length = grow();

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
        .to(head, { yPercent: -100, opacity: 0, duration: screen() * HEAD_SHARE }, pause)
        .fromTo(
          media,
          { y: () => shift() },
          { y: 0, duration: screen() * HEAD_SHARE, immediateRender: true },
          pause
        )
        .fromTo(
          scene,
          { clipPath: () => closed() },
          { clipPath: () => opened(), duration: length },
          start
        )
        .fromTo(
          image,
          { scale: () => startScale(), x: () => startShift(), y: () => startLift(), transformOrigin: 'top left' },
          { scale: 1, x: 0, y: 0, duration: length },
          start
        )
        /* Начало явное: twin `to` берёт стартовое значение из текущего, и в WebKit
           оно оказывалось единицей — подписи просвечивали в окне превью. */
        .fromTo(
          features,
          { opacity: 0 },
          { opacity: 1, duration: screen() * FEATURES_SHARE, immediateRender: true },
          start + length
        );

      return () => delete section.dataset.morph;
    });
  }, section);

  return () => context.revert();
}
