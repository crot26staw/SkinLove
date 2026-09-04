import { gsap } from 'gsap';

/* Появление первого экрана при загрузке: строки заголовка, подзаголовок
   и кнопка по очереди опускаются сверху и проявляются. Порядок — по DOM,
   то есть сверху вниз.

   До запуска JS контент прячет модификатор `hero--intro` (см. hero.css) —
   иначе текст успевает мигнуть до старта. Модификатор снимается и стартовое
   состояние ставится в одном синхронном шаге, между ними кадра нет.

   Старт ждёт загрузки шрифтов, иначе текст перевёрстывается на полпути;
   на медленной сети ожидание ограничено, чтобы экран не стоял пустым. */

const INTRO_CLASS = 'hero--intro';
const OFFSET = -40;
const DURATION = 0.9;
const STAGGER = 0.12;
const FONTS_TIMEOUT = 800;

const fontsReady = () =>
  Promise.race([
    document.fonts?.ready ?? Promise.resolve(),
    new Promise((resolve) => setTimeout(resolve, FONTS_TIMEOUT))
  ]);

export function initHeroIntro() {
  const hero = document.querySelector('[data-section="hero"]');
  const items = [...(hero?.querySelectorAll('[data-hero-intro]') ?? [])];

  if (!hero || !items.length) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      const timeline = gsap
        .timeline({ paused: true })
        .fromTo(
          items,
          { y: OFFSET, opacity: 0 },
          { y: 0, opacity: 1, duration: DURATION, ease: 'power3.out', stagger: STAGGER, clearProps: 'all' }
        );

      hero.classList.remove(INTRO_CLASS);
      fontsReady().then(() => timeline.play());
    });

    mm.add('(prefers-reduced-motion: reduce)', () => hero.classList.remove(INTRO_CLASS));
  }, hero);

  return () => context.revert();
}
