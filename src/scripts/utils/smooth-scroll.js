import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

import { TOUCH } from './media.js';
import { allowHorizontalTouchScroll } from './horizontal-scroll.js';

/* Инерционная прокрутка. Множитель — темп страницы за один щелчок колеса.
   Замедление здесь стоило дорого: страница длинная из-за закреплённых сцен,
   и на мыши это читалось как вязкость. */
const SPEED = 1;

/* Тач-устройства: прокрутку ведёт ScrollTrigger.normalizeScroll, а не Lenis
   и не браузер. Палец страница слушается один к одному, а после отпускания
   долетает по инерции: TOUCH_MOMENTUM — её длительность в секундах (у GSAP
   по умолчанию 2.8), дистанция долёта ей пропорциональна.

   Почему не Lenis: тач-прокрутку он ведёт нативно, но вешает на window
   не-passive touch-слушатели, и браузер перед каждым сдвигом пальца ждёт
   главный поток — прокрутка вздрагивает. Почему не нативная: сцены догоняют
   её по событию scroll на кадр позже и дрожат; нормализатор двигает страницу
   и сцены в одном тике GSAP. */
const TOUCH_MOMENTUM = 1.2;

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

/* Остановка прокрутки под открытым меню или попапом. Lenis сам вешает на <html>
   класс lenis-stopped, а его стили закрывают overflow. Нормализатор на время
   блокировки выключается: иначе он прокручивал бы страницу под меню и глушил
   нативную прокрутку внутри попапа. Без обоих overflow закрывается здесь. */
export function lockScroll(locked) {
  const normalizer = ScrollTrigger.normalizeScroll();

  if (instance) {
    if (locked) {
      instance.stop();
    } else {
      instance.start();
    }

    return;
  }

  if (normalizer) {
    if (locked) {
      normalizer.disable();
    } else {
      normalizer.enable();
    }
  }

  document.documentElement.style.overflow = locked ? 'hidden' : '';
}

export function initSmoothScroll() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  if (window.matchMedia(TOUCH).matches) {
    ScrollTrigger.normalizeScroll({ momentum: TOUCH_MOMENTUM });

    /* Горизонтальные ряды (отзывы ниже 1024) листаются нативно, см. модуль. */
    const releaseRows = allowHorizontalTouchScroll();

    return () => {
      releaseRows();
      ScrollTrigger.normalizeScroll(false);
    };
  }

  const lenis = new Lenis({
    wheelMultiplier: SPEED,
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
