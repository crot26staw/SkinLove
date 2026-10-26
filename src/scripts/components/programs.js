import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { DESKTOP, MOTION } from '../utils/media.js';
import { enableDragScroll } from '../utils/drag-scroll.js';

/* Фильтр видов программ: вкладка показывает карточки своей категории
   (data-category), «все» — все. Разметка рассчитана на Repeater: карточек
   и вкладок любое число.

   Раскладка по макету: во «Все виды» ряды по 4 и 3 карточки через один,
   в категории — по 3. Ряд из одной карточки не остаётся: она забирает
   соседа из предыдущего ряда. Ширину каждой видимой карточки модуль ставит
   в --program-span (12-колоночная сетка, sections/programs.css); ниже 1024
   колонки задаёт CSS, и переменная не действует.

   Вариант с постоянной шириной (data-programs-columns на секции, выездные
   услуги): в ряду столько карточек, сколько колонок, у всех одна ширина,
   неполный ряд встаёт по центру (--program-start).

   Необязательно: подпись выбранной вкладки (data-programs-caption со значением
   вкладки) и лимит (data-programs-limit) — сверх него карточки прячутся
   до нажатия data-programs-more, подписи кнопки — data-label-more / -less.
   Лимит действует только на десктопе: ниже 1024 карточки и так листаются
   вбок, все сразу, без кнопки.

   Высота секции после смены вкладки другая — триггеры ниже пересчитываются.

   Ниже 1024 вкладки стоят в одну строку и листаются вбок: пальцем, тачпадом
   или протяжкой мышью (utils/drag-scroll.js). Нажатая вкладка выезжает
   в середину ряда, а край, за которым есть ещё вкладки, затухает
   (data-fade на обёртке data-programs-tabs, sections/programs.css):
   маска на самом прокручиваемом ряду заставляла Safari перерисовывать
   его на каждом кадре прокрутки. */

const ALL = 'all';
const COLUMNS = 12;
const ROWS_ALL = [4, 3];
const ROWS_CATEGORY = [3];

export function initPrograms() {
  document.querySelectorAll('[data-programs]').forEach(setup);
}

function setup(section) {
  const row = section.querySelector('[data-programs-filters]');
  const tabs = row?.closest('[data-programs-tabs]') ?? row;
  const filters = [...section.querySelectorAll('[data-programs-filter]')];
  const grid = section.querySelector('[data-programs-grid]');
  const cards = [...section.querySelectorAll('[data-program]')];

  if (!filters.length || !grid || !cards.length) return;

  const motion = window.matchMedia(MOTION);
  const desktop = window.matchMedia(DESKTOP);
  const columns = Number(section.dataset.programsColumns) || 0;
  const maxCards = Number(section.dataset.programsLimit) || Infinity;
  const captions = [...section.querySelectorAll('[data-programs-caption]')];
  const more = section.querySelector('[data-programs-more]');
  const moreLabel = more?.querySelector('.button__label');

  let current = null;
  let expanded = false;

  const apply = (value, animate) => {
    const matching = cards.filter((card) => value === ALL || card.dataset.category === value);
    const limit = desktop.matches ? maxCards : Infinity;
    const visible = expanded ? matching : matching.slice(0, limit);

    filters.forEach((filter) => {
      filter.setAttribute('aria-pressed', String(filter.dataset.programsFilter === value));
    });

    captions.forEach((caption) => {
      caption.hidden = caption.dataset.programsCaption !== value;
    });

    cards.forEach((card) => {
      card.hidden = !visible.includes(card);
    });

    if (more) {
      more.hidden = matching.length <= limit;
      more.setAttribute('aria-expanded', String(expanded));
      if (moreLabel) moreLabel.textContent = expanded ? more.dataset.labelLess : more.dataset.labelMore;
    }

    if (columns) {
      place(visible, columns);
    } else {
      span(visible, layout(visible.length, value === ALL ? ROWS_ALL : ROWS_CATEGORY));
    }

    current = value;

    if (!animate) return;

    grid.scrollLeft = 0;
    ScrollTrigger.refresh();

    if (motion.matches) {
      gsap.fromTo(
        visible,
        { autoAlpha: 0, y: 20 },
        { autoAlpha: 1, y: 0, duration: 0.4, ease: 'power2.out', stagger: 0.04, overwrite: true }
      );
    }
  };

  filters.forEach((filter) => {
    filter.addEventListener('click', () => {
      if (row) centre(row, filter, motion.matches);

      if (filter.getAttribute('aria-pressed') === 'true') return;

      expanded = false;
      apply(filter.dataset.programsFilter, true);
    });
  });

  more?.addEventListener('click', () => {
    expanded = !expanded;
    apply(current, true);
  });

  desktop.addEventListener('change', () => apply(current, false));

  if (row) {
    const fade = () => markFade(row, tabs);

    enableDragScroll(row);
    row.addEventListener('scroll', fade, { passive: true });
    new ResizeObserver(fade).observe(row);
  }

  const pressed = filters.find((filter) => filter.getAttribute('aria-pressed') === 'true');

  apply(pressed?.dataset.programsFilter ?? ALL, false);
}

/* Ширина по рядам переменной длины: в ряду из size карточек каждая
   занимает COLUMNS / size колонок. */
function span(visible, rows) {
  let index = 0;

  rows.forEach((size) => {
    visible.slice(index, index + size).forEach((card) => {
      card.style.setProperty('--program-span', String(COLUMNS / size));
      card.style.removeProperty('--program-start');
    });
    index += size;
  });
}

/* Постоянная ширина: ряды по columns с тем же правилом хвоста, что в layout
   (4 карточки при трёх колонках — 2 и 2). Первая карточка неполного ряда
   сдвигается так, чтобы ряд стоял по центру; остальные встают за ней сами. */
function place(visible, columns) {
  const width = COLUMNS / columns;
  let index = 0;

  layout(visible.length, [columns]).forEach((size) => {
    visible.slice(index, index + size).forEach((card, position) => {
      card.style.setProperty('--program-span', String(width));

      if (position === 0 && size < columns) {
        card.style.setProperty('--program-start', String((COLUMNS - size * width) / 2 + 1));
      } else {
        card.style.removeProperty('--program-start');
      }
    });
    index += size;
  });
}

/* Вкладка — в середину ряда (если ряд не листается, сдвигать нечего). */
function centre(row, tab, smooth) {
  const offset = tab.getBoundingClientRect().left - row.getBoundingClientRect().left;

  row.scrollTo({
    left: row.scrollLeft + offset - (row.clientWidth - tab.offsetWidth) / 2,
    behavior: smooth ? 'smooth' : 'auto'
  });
}

/* С какой стороны за краем ряда ещё есть вкладки: start, end или both. */
function markFade(row, target) {
  const max = row.scrollWidth - row.clientWidth;
  const start = row.scrollLeft > 1;
  const end = row.scrollLeft < max - 1;

  const fade = start && end ? 'both' : start ? 'start' : end ? 'end' : null;

  if (fade === (target.dataset.fade ?? null)) return;

  if (fade) {
    target.dataset.fade = fade;
  } else {
    delete target.dataset.fade;
  }
}

/* Размеры рядов: по шаблону по кругу, хвост из одной карточки
   переезжает к соседу — последние два ряда делят его поровну. */
function layout(count, pattern) {
  const rows = [];
  let left = count;

  while (left > 0) {
    const size = Math.min(pattern[rows.length % pattern.length], left);

    rows.push(size);
    left -= size;
  }

  if (rows.length > 1 && rows.at(-1) === 1) {
    rows[rows.length - 2] -= 1;
    rows[rows.length - 1] += 1;
  }

  return rows;
}
