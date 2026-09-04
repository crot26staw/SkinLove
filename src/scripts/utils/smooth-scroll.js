import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

/* Инерционная прокрутка. Множитель — темп страницы за один щелчок колеса.
   Замедление здесь стоило дорого: страница длинная из-за закреплённых сцен,
   и на мыши это читалось как вязкость. */
const SPEED = 1;

let instance = null;

/* Прокрутка к позиции из скриптов: через Lenis, пока он живёт, иначе
   нативно. Нативный window.scrollTo Lenis перехватывает и возвращает
   страницу к своей цели, поэтому напрямую им пользоваться нельзя. */
export function scrollTo(top) {
  if (instance) {
    instance.scrollTo(top);
  } else {
    window.scrollTo({ top, behavior: 'smooth' });
  }
}

export function initSmoothScroll() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const lenis = new Lenis({
    wheelMultiplier: SPEED,
    touchMultiplier: SPEED,
    anchors: true,
    autoRaf: false
  });

  /* Скролл крутит гсаповский тикер, а не свой rAF: так кадр анимации и кадр
     прокрутки считаются в одном такте и картинка не дрожит. */
  const update = (time) => lenis.raf(time * 1000);

  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add(update);
  gsap.ticker.lagSmoothing(0);
  instance = lenis;

  return () => {
    gsap.ticker.remove(update);
    gsap.ticker.lagSmoothing(500, 33);
    lenis.destroy();
    instance = null;
  };
}
