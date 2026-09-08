import { gsap } from 'gsap';

import { DESKTOP, MOTION } from '../utils/media.js';

/* Карусель (carousel): круглые карточки задач медленно и без остановки едут
   влево по кольцу, у центра вырастают, к краям уменьшаются. Размер зависит
   от текущего расстояния до центра ряда, а не от места в разметке: положение
   в кольце измеряется в шагах от центра, шаг от центра уменьшает карточку
   на шестую часть, дальше двух шагов не уменьшает — 600 → 500 → 400 в макете.

   Размер меняется масштабом (transform), не шириной; зазоры между соседями
   при этом держатся ровно: ряд раскладывается от опорной карточки — той, что
   ближе всех к центру, — вправо и влево по фактическим ширинам. Опорная
   стоит на своём месте в кольце, помноженном на расстояние между центрами
   её и соседа с той стороны, куда она наклонена: в момент смены опорной
   обе формулы дают одно и то же, и ряд не дёргается.

   Если карточек мало и ряд не закрывает экран, набор дописывается копиями
   (aria-hidden). Едет только пока секция на экране. При отключённых
   анимациях ряд стоит, размеры — по расстоянию от середины разметки.
   Ниже 1024 карусели нет: задачи стоят списком строк (tasks.css).

   Описание сцен и их имена — в ANIMATIONS.md. */

/* Скорость: шагов кольца в секунду. */
const SPEED = 0.16;

/* Дальше скольких шагов от центра карточка не уменьшается. */
const MAX_RANK = 2;

/* На сколько один шаг от центра уменьшает карточку. */
const SHRINK = 1 / 6;

export function initTasks() {
  const cleanups = [...document.querySelectorAll('[data-tasks]')].map(setup).filter(Boolean);

  if (!cleanups.length) return;

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(section) {
  const list = section.querySelector('[data-tasks-list]');
  const originals = [...section.querySelectorAll('[data-task]')];

  if (!list || !originals.length) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add(`(prefers-reduced-motion: reduce) and ${DESKTOP}`, () => {
      const center = (originals.length - 1) / 2;

      originals.forEach((card, index) => {
        card.style.setProperty('--task-rank', String(Math.min(Math.abs(index - center), MAX_RANK)));
      });
      section.dataset.tasks = 'on';

      return () => {
        originals.forEach((card) => card.style.removeProperty('--task-rank'));
        delete section.dataset.tasks;
      };
    });

    mm.add(`${MOTION} and ${DESKTOP}`, () => {
      section.dataset.scene = 'on';

      let base = 0;
      let gap = 0;
      let center = 0;

      const measure = () => {
        base = originals[0].offsetWidth;
        gap = parseFloat(getComputedStyle(list).columnGap) || 0;
        center = list.clientWidth / 2;
      };

      measure();

      /* Копии до тех пор, пока ряд из самых маленьких карточек не закроет
         экран с запасом в карточку с каждой стороны. */
      const clones = [];
      const minWidth = (count) => count * base * (1 - MAX_RANK * SHRINK) + (count - 1) * gap;

      while (minWidth(originals.length + clones.length) < center * 2 + base * 2) {
        const source = originals[clones.length % originals.length];
        const clone = source.cloneNode(true);

        clone.setAttribute('aria-hidden', 'true');
        list.append(clone);
        clones.push(clone);
      }

      const cards = [...originals, ...clones];
      const count = cards.length;
      const scaleOf = (u) => 1 - Math.min(Math.abs(u), MAX_RANK) * SHRINK;
      const widthOf = (u) => base * scaleOf(u);

      /* Место в кольце: от −count/2 до count/2, ноль — центр ряда. */
      const ring = (index, phase) => {
        let u = (index + phase) % count;

        if (u < 0) u += count;
        if (u >= count / 2) u -= count;

        return u;
      };

      const place = (card, x, scale) => gsap.set(card, { x: x - base / 2, scale });

      const layout = (phase) => {
        const items = cards
          .map((card, index) => ({ card, u: ring(index, phase) }))
          .sort((a, b) => a.u - b.u);

        let anchor = 0;

        items.forEach((item, index) => {
          if (Math.abs(item.u) < Math.abs(items[anchor].u)) anchor = index;
        });

        const { u } = items[anchor];
        const lean = u >= 0 ? -1 : 1;
        const pitch = (widthOf(u) + widthOf(u + lean)) / 2 + gap;
        const positions = new Array(count);

        positions[anchor] = center + u * pitch;

        for (let i = anchor + 1; i < count; i++) {
          positions[i] = positions[i - 1] + (widthOf(items[i - 1].u) + widthOf(items[i].u)) / 2 + gap;
        }

        for (let i = anchor - 1; i >= 0; i--) {
          positions[i] = positions[i + 1] - (widthOf(items[i + 1].u) + widthOf(items[i].u)) / 2 - gap;
        }

        items.forEach((item, index) => place(item.card, positions[index], scaleOf(item.u)));
      };

      /* В начале по центру стоит средняя карточка из разметки. */
      let phase = -(originals.length - 1) / 2;

      const tick = (_, delta) => {
        phase -= (delta / 1000) * SPEED;
        layout(phase);
      };

      layout(phase);

      const visibility = new IntersectionObserver(([entry]) => {
        if (entry.isIntersecting) {
          gsap.ticker.add(tick);
        } else {
          gsap.ticker.remove(tick);
        }
      });

      const resize = new ResizeObserver(() => {
        measure();
        layout(phase);
      });

      visibility.observe(section);
      resize.observe(list);

      return () => {
        gsap.ticker.remove(tick);
        visibility.disconnect();
        resize.disconnect();
        clones.forEach((clone) => clone.remove());
        gsap.set(originals, { clearProps: 'transform' });
        delete section.dataset.scene;
      };
    });
  }, section);

  return () => context.revert();
}
