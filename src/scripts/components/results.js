import { gsap } from 'gsap';

import { DESKTOP, MOTION, TABLET } from '../utils/media.js';

/* Лента результатов: стрелки сдвигают ленту на карточку. Дальше последней
   карточки, прижатой к правому краю секции, лента не едет, поэтому последний
   шаг может быть короче; на краях стрелка гаснет. Разметка рассчитана
   на Repeater: карточек любое число, если все помещаются — стрелки неактивны.
   Без скрипта лента прокручивается нативно (results.css).

   Ниже 1024 лента центрирована: активная карточка стоит посередине экрана
   и отмечена aria-current (её растит result-card.css), стрелки ходят
   по карточкам от первой до последней.

   Лента листается сама: раз в AUTOPLAY_DELAY секунд плавный шаг вперёд,
   с последней карточки — обратно к первой. Едет, когда секция заехала
   в середину экрана, и под курсором тоже: при прокрутке колесом курсор
   почти всегда оказывается над лентой. Пауза — пока внутри фокус
   с клавиатуры; стрелка перезапускает отсчёт. При reduced motion
   автопролистывания нет.

   На десктопе с анимациями ленту ведёт прокрутка (animations/results.js):
   стрелки скрыты, автопролистывания нет, сдвиг здесь не трогается. */

const DURATION = 0.5;
const AUTOPLAY_DELAY = 4;
const AUTOPLAY_DURATION = 1.2;

export function initResults() {
  document.querySelectorAll('[data-results]').forEach(setup);
}

function setup(section) {
  const viewport = section.querySelector('[data-results-viewport]');
  const track = section.querySelector('[data-results-track]');
  const previous = section.querySelector('[data-results-prev]');
  const next = section.querySelector('[data-results-next]');
  const cards = track ? [...track.children] : [];

  if (!viewport || !track || !previous || !next || cards.length < 2) return;

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const tablet = window.matchMedia(TABLET);
  const scrolled = window.matchMedia(`${DESKTOP} and ${MOTION}`);
  let index = 0;
  let inView = false;
  let focused = false;

  const step = () => cards[0].offsetWidth + (parseFloat(getComputedStyle(track).columnGap) || 0);

  /* Сколько ленты видно: от её левого края до правого поля секции. */
  const room = () => {
    const edge = section.getBoundingClientRect().right - (parseFloat(getComputedStyle(section).paddingRight) || 0);

    return edge - viewport.getBoundingClientRect().left;
  };

  const limit = () => Math.max(0, track.scrollWidth - room());
  const last = () => (tablet.matches ? cards.length - 1 : Math.ceil(limit() / step()));

  /* Сдвиг ленты: на десктопе — на карточку, до упора; на планшете — так,
     чтобы середина активной карточки встала в середину окна. */
  const centre = (card) =>
    card.offsetLeft - viewport.offsetLeft + card.offsetWidth / 2 - viewport.clientWidth / 2;
  const offset = (i) => (tablet.matches ? centre(cards[i]) : Math.min(i * step(), limit()));

  /* duration — длительность сдвига; 0 — без анимации. */
  const render = (duration) => {
    if (scrolled.matches) return;

    index = Math.min(Math.max(index, 0), last());
    previous.disabled = index === 0;
    next.disabled = index >= last();

    cards.forEach((card, i) => {
      if (tablet.matches && i === index) {
        card.setAttribute('aria-current', 'true');
      } else {
        card.removeAttribute('aria-current');
      }
    });

    gsap.to(track, {
      x: -offset(index),
      duration: reduceMotion.matches ? 0 : duration,
      ease: duration === AUTOPLAY_DURATION ? 'power2.inOut' : 'power2.out',
      overwrite: true
    });
  };

  const advance = () => {
    index = index >= last() ? 0 : index + 1;
    render(AUTOPLAY_DURATION);
    timer.restart(true);
  };

  const timer = gsap.delayedCall(AUTOPLAY_DELAY, advance).pause();

  /* Отсчёт идёт заново после каждой паузы и ручного шага. */
  const sync = () => {
    const running = inView && !focused && !reduceMotion.matches && !scrolled.matches && last() > 0;

    if (running && timer.paused()) {
      timer.restart(true);
    } else if (!running) {
      timer.pause();
    }
  };

  const go = (delta) => {
    index += delta;
    render(DURATION);
    timer.pause();
    sync();
  };

  previous.addEventListener('click', () => go(-1));
  next.addEventListener('click', () => go(1));

  /* Фокус после клика мышью по стрелке паузу не держит — только с клавиатуры. */
  section.addEventListener('focusin', (event) => {
    focused = event.target.matches(':focus-visible');
    sync();
  });
  section.addEventListener('focusout', (event) => {
    focused = section.contains(event.relatedTarget) && event.relatedTarget.matches(':focus-visible');
    sync();
  });

  /* Секция заехала в среднюю половину экрана — так порог не зависит
     от её высоты, на телефоне она бывает выше экрана. */
  new IntersectionObserver(
    ([entry]) => {
      inView = entry.isIntersecting;
      sync();
    },
    { rootMargin: '-25% 0px' }
  ).observe(section);

  new ResizeObserver(() => {
    render(0);
    sync();
  }).observe(section);
  tablet.addEventListener('change', () => render(0));

  /* Переход в режим прокрутки: лента возвращается к началу до того, как сцена
     запишет её стартовую позицию, — этот слушатель создан раньше сцены. */
  scrolled.addEventListener('change', () => {
    if (scrolled.matches) {
      index = 0;
      gsap.set(track, { x: 0, overwrite: true });
    } else {
      render(0);
    }

    sync();
  });
  reduceMotion.addEventListener('change', sync);

  section.dataset.results = 'on';
  render(0);
}
