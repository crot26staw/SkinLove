import { gsap } from 'gsap';

import { dissolveLength } from './timing.js';

/* Растворение (dissolve): секция замирает и гаснет целиком — вместе с фоном.
   Под ней уже стоит следующая секция (underlap) — неподвижно, всю фазу,
   поэтому подмена основания не читается: на экране просто остаётся ровный фон.

   Описание сцен и их имена — в ANIMATIONS.md. */
export function initAboutDissolve() {
  const section = document.querySelector('[data-section="about"]');
  const next = section?.nextElementSibling;

  if (!section || !next) return;

  /* Подкладка = высота секции + длина фазы. Слагаемое с фазой обязательно:
     закрепляя секцию, GSAP подменяет её распоркой в высоту секции плюс длину
     пина, и всё, что идёт следом, съезжает вниз на эту длину. Без компенсации
     следующая секция не ждёт под растворяющейся, а едет снизу вверх наравне
     с прокруткой, и в конце фазы её резко прибивает собственным пином. */
  const applyUnderlap = () =>
    next.style.setProperty('--underlap', `${section.offsetHeight + dissolveLength()}px`);

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      next.dataset.underlap = 'on';
      applyUnderlap();

      gsap.to(section, {
        opacity: 0,
        ease: 'none',
        scrollTrigger: {
          trigger: section,
          start: 'top top',
          end: () => `+=${dissolveLength()}`,
          pin: true,
          scrub: true,
          invalidateOnRefresh: true,
          onRefreshInit: applyUnderlap
        }
      });

      return () => {
        delete next.dataset.underlap;
        next.style.removeProperty('--underlap');
      };
    });
  }, section);

  return () => context.revert();
}
