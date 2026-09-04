import { gsap } from 'gsap';

import { blindsOpen } from './blinds.js';
import { createOverlayScene, crossfade, hideLayers } from './overlay-scene.js';
import { screen } from './timing.js';
import { scrollTo } from '../utils/smooth-scroll.js';
import { previousSection } from '../utils/siblings.js';

/* Услуги с выездом на сцене «Наслоение» (overlay-scene.js): при прокрутке
   фото следующей услуги открывается снизу поверх текущего, описание и название
   плавно подменяются, во вкладках зажигается следующая. Вкладки кликабельны:
   клик прокручивает к позиции услуги в сцене.

   Фото, описания и названия — три прохода по одному набору, модуль связывает
   их по индексу. Без сцены (и при отключённых анимациях) видна активная
   услуга, остальные спрятаны атрибутом hidden, вкладки переключают её
   на месте.

   Открывается блок жалюзи, как подвал: полосы расходятся, пока секция
   входит в кадр, и раскрываются к моменту закрепления.

   Описание сцен и их имена — в ANIMATIONS.md. */

export function initProcedures() {
  const cleanups = [...document.querySelectorAll('[data-procedures]')].map(setup).filter(Boolean);

  if (!cleanups.length) return;

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(section) {
  const list = section.querySelector('[data-procedures-tablist]');
  const tabs = [...section.querySelectorAll('[data-procedures-tab]')];
  const frames = [...section.querySelectorAll('[data-procedures-frame]')];
  const panels = [...section.querySelectorAll('[data-procedures-panel]')];
  const captions = [...section.querySelectorAll('[data-procedures-caption]')];
  const bars = [...section.querySelectorAll('[data-blind]')];
  const count = tabs.length;

  if (!list || count < 2 || frames.length !== count || panels.length !== count || captions.length !== count) {
    return;
  }

  const layers = [...frames, ...panels, ...captions];
  let current = Math.max(0, tabs.findIndex((tab) => tab.getAttribute('aria-selected') === 'true'));
  let scene = null;

  /* Вкладки отражают активный элемент всегда; слои — только без сцены,
     в сцене их состояние ведёт таймлайн. */
  const select = (index) => {
    tabs.forEach((tab, i) => {
      const active = i === index;

      tab.setAttribute('aria-selected', String(active));
      tab.tabIndex = active ? 0 : -1;

      if (!scene) {
        frames[i].hidden = !active;
        panels[i].hidden = !active;
        captions[i].hidden = !active;
      }
    });

    current = index;
  };

  const go = (index, focus = false) => {
    const target = Math.min(Math.max(index, 0), count - 1);

    if (scene) {
      scrollTo(scene.position(target));
    } else {
      select(target);
    }

    if (focus) tabs[target].focus();
  };

  const onKeydown = (event) => {
    const steps = {
      ArrowDown: current + 1,
      ArrowRight: current + 1,
      ArrowUp: current - 1,
      ArrowLeft: current - 1,
      Home: 0,
      End: count - 1
    };

    if (!(event.key in steps)) return;

    event.preventDefault();
    go(steps[event.key], true);
  };

  const onClicks = tabs.map((tab, index) => {
    const onClick = () => go(index);

    tab.addEventListener('click', onClick);

    return () => tab.removeEventListener('click', onClick);
  });

  list.addEventListener('keydown', onKeydown);
  select(current);

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      const previous = previousSection(section);

      section.dataset.scene = 'on';

      if (previous) {
        section.style.setProperty('--blinds-color', getComputedStyle(previous).backgroundColor);
      }

      layers.forEach((layer) => {
        layer.hidden = false;
      });

      hideLayers([...panels.slice(1), ...captions.slice(1)]);

      if (bars.length) {
        gsap.to(bars, {
          ...blindsOpen(screen()),
          ease: 'none',
          scrollTrigger: {
            trigger: section,
            start: 'top 90%',
            end: 'top top',
            scrub: true,
            invalidateOnRefresh: true
          }
        });
      }

      scene = createOverlayScene({
        section,
        frames,
        build: (timeline, i, at, step) =>
          crossfade(timeline, [panels[i - 1], captions[i - 1]], [panels[i], captions[i]], at, step),
        onIndex: (index) => {
          if (index !== current) select(index);
        }
      });

      return () => {
        scene = null;
        delete section.dataset.scene;
        section.style.removeProperty('--blinds-color');
        select(current);
      };
    });
  }, section);

  return () => {
    context.revert();
    list.removeEventListener('keydown', onKeydown);
    onClicks.forEach((unbind) => unbind());
  };
}
