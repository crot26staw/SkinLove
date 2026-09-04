import { gsap } from 'gsap';

import { nextSection } from '../utils/siblings.js';

/* Разъезд (spread): первый экран липкий, следующая секция наезжает на него
   сверху. Элементы с data-spread-left уходят за левый край, с data-spread-right —
   за правый, а поверх фото проявляется слой (data-spread-veil) цвета следующей
   секции — тот же приём, что «Перекрас» на главной.

   Разъезд начинается раньше наезда: у первого экрана есть разбег — отрезок
   прокрутки, на котором он ещё стоит один, а текст уже едет. Следующая секция
   показывается снизу, когда текст ушёл на половину, и встаёт на место, когда
   её верх дошёл до верха экрана; слой проявляется вместе с наездом. Разбег
   даёт отступ после секции, который ставит скрипт через --spread-lead:
   без сцены его нет, и секции идут встык.

   До какой степени высветлять фото, секция задаёт сама через --spread-veil-max
   (по умолчанию до непрозрачности). Секции со скруглённым верхом нужно меньше:
   в углах фото остаётся видно ещё почти экран прокрутки, и закрашенное
   полностью оно сольётся с фоном — полукруг пропадёт.

   Описание сцен и их имена — в ANIMATIONS.md. */

/* Ход текста — в долях экрана прокрутки. */
const SPREAD_SHARE = 0.7;

/* Какая часть хода текста проходит до появления следующей секции. */
const LEAD_SHARE = 0.5;

export function initSpread() {
  const contexts = [...document.querySelectorAll('[data-spread]')]
    .map(createSpread)
    .filter(Boolean);

  if (!contexts.length) return;

  return () => contexts.forEach((context) => context.revert());
}

function createSpread(section) {
  const veil = section.querySelector('[data-spread-veil]');
  const left = [...section.querySelectorAll('[data-spread-left]')];
  const right = [...section.querySelectorAll('[data-spread-right]')];
  const next = nextSection(section);

  if (!veil || !next || !(left.length || right.length)) return;

  /* Ход — собственная ширина плюс поле секции: элемент уходит за край целиком. */
  const gutter = () => parseFloat(getComputedStyle(section).paddingLeft) || 0;
  const travel = (element) => element.offsetWidth + gutter();
  const veilMax = () =>
    parseFloat(getComputedStyle(section).getPropertyValue('--spread-veil-max')) || 1;

  const lead = () => window.innerHeight * SPREAD_SHARE * LEAD_SHARE;
  const applyLead = () => section.style.setProperty('--spread-lead', `${lead()}px`);

  return gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      section.dataset.spread = 'on';
      applyLead();
      gsap.set(veil, { backgroundColor: getComputedStyle(next).backgroundColor });

      /* Длительности — в долях экрана: разбег, потом наезд длиной в экран. */
      const timeline = gsap.timeline({
        defaults: { ease: 'none' },
        scrollTrigger: {
          trigger: next,
          start: () => `top bottom+=${lead()}`,
          end: 'top top',
          scrub: true,
          invalidateOnRefresh: true,
          onRefreshInit: applyLead
        }
      });

      const overlap = SPREAD_SHARE * LEAD_SHARE;

      if (left.length) {
        timeline.to(left, { x: (_, element) => -travel(element), duration: SPREAD_SHARE }, 0);
      }

      if (right.length) {
        timeline.to(right, { x: (_, element) => travel(element), duration: SPREAD_SHARE }, 0);
      }

      timeline.to(veil, { opacity: veilMax, duration: 1 }, overlap);

      return () => {
        section.dataset.spread = '';
        section.style.removeProperty('--spread-lead');
      };
    });
  }, section);
}
