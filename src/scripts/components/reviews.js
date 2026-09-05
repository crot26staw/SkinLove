import { gsap } from 'gsap';

import { TABLET } from '../utils/media.js';

/* Имена — вкладки, отзывы — панели. Стрелки листают по кругу.
   Разметка рассчитана на Repeater: имена и отзывы это два прохода
   по одному набору, поэтому модуль связывает их по индексу.

   Ниже 1024 вкладок нет (CSS их прячет): все отзывы стоят в ряд
   и листаются прокруткой, поэтому панели не прячутся. */
export function initReviews() {
  document.querySelectorAll('[data-reviews]').forEach(setup);
}

function setup(root) {
  const list = root.querySelector('[data-review-tablist]');
  const tabs = [...root.querySelectorAll('[data-review-tab]')];
  const panels = [...root.querySelectorAll('[data-review-panel]')];
  const previous = root.querySelector('[data-review-prev]');
  const next = root.querySelector('[data-review-next]');

  if (!list || tabs.length < 2 || tabs.length !== panels.length) return;

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const tablet = window.matchMedia(TABLET);
  let current = Math.max(0, tabs.findIndex((tab) => tab.getAttribute('aria-selected') === 'true'));

  const render = (target, { animate = false, focus = false } = {}) => {
    tabs.forEach((tab, index) => {
      const active = index === target;

      tab.setAttribute('aria-selected', String(active));
      tab.tabIndex = active ? 0 : -1;
      panels[index].hidden = !active && !tablet.matches;
    });

    current = target;

    if (focus) tabs[target].focus();

    if (animate && !reduceMotion.matches) {
      gsap.fromTo(
        panels[target],
        { opacity: 0 },
        { opacity: 1, duration: 0.32, ease: 'power2.out' }
      );
    }
  };

  const go = (index, focus = false) => {
    const target = (index + tabs.length) % tabs.length;

    if (target === current) return;

    render(target, { animate: true, focus });
  };

  tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => go(index));
  });

  previous?.addEventListener('click', () => go(current - 1));
  next?.addEventListener('click', () => go(current + 1));

  list.addEventListener('keydown', (event) => {
    const steps = {
      ArrowDown: current + 1,
      ArrowRight: current + 1,
      ArrowUp: current - 1,
      ArrowLeft: current - 1,
      Home: 0,
      End: tabs.length - 1
    };

    if (!(event.key in steps)) return;

    event.preventDefault();
    go(steps[event.key], true);
  });

  tablet.addEventListener('change', () => render(current));
  render(current);
}
