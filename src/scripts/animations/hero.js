import { gsap } from 'gsap';

/* Пока следующая секция наезжает на липкий hero, открытая часть hero
   перекрашивается в её фон — экран уходит не срезом, а растворением. */
export function initHeroBackdrop() {
  const hero = document.querySelector('[data-section="hero"]');
  const veil = hero?.querySelector('[data-hero-veil]');
  const next = hero?.nextElementSibling;

  if (!hero || !veil || !next) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      gsap.set(veil, { backgroundColor: getComputedStyle(next).backgroundColor });

      gsap.to(veil, {
        opacity: 1,
        ease: 'none',
        scrollTrigger: {
          trigger: next,
          start: 'top bottom',
          end: 'top top',
          scrub: true
        }
      });
    });
  }, hero);

  return () => context.revert();
}
