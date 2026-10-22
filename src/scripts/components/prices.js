import { TABLET } from '../utils/media.js';

/* Длинный прайс в панели процедуры: видны первые data-prices-limit строк,
   остальные открывает кнопка «Развернуть полный прайс». Ниже 1024 карточки
   стоят в ряд и должны быть одной высоты, поэтому лимит общий для секции:
   data-prices-limit-compact, а если у какой-то карточки строк меньше —
   по ней.
   Раскрытый список не растягивает панель, а прокручивается в своей высоте
   (procedure-panel.css), поэтому на время раскрытия ему ставится
   data-lenis-prevent: колесо над списком листает его, а не страницу.

   Без JS видны все строки, кнопка спрятана атрибутом hidden. Если строк
   не больше лимита, кнопка так и остаётся спрятанной. При смене ширины
   прайс сворачивается под новый лимит. */
export function initPrices() {
  const blocks = [...document.querySelectorAll('[data-prices]')];

  if (!blocks.length) return;

  const compact = window.matchMedia(TABLET);
  const rowCount = (block) => block.querySelector('[data-prices-list]')?.children.length ?? 0;
  const groupLimit = (block) => {
    const group = block.closest('[data-section]') ?? document;
    const counts = blocks.filter((other) => group.contains(other)).map(rowCount);

    return Math.min(Number(block.dataset.pricesLimitCompact || block.dataset.pricesLimit), ...counts);
  };
  const controls = blocks.map((block) => setup(block, compact, groupLimit(block))).filter(Boolean);

  if (!controls.length) return;

  const onChange = () => controls.forEach((control) => control.reset());

  compact.addEventListener('change', onChange);

  return () => {
    compact.removeEventListener('change', onChange);
    controls.forEach((control) => control.unbind());
  };
}

function setup(block, compact, compactLimit) {
  const list = block.querySelector('[data-prices-list]');
  const toggle = block.querySelector('[data-prices-toggle]');
  const limits = { wide: Number(block.dataset.pricesLimit), compact: compactLimit };

  if (!list || !toggle || !limits.wide || !limits.compact) return;

  const rows = [...list.children];
  let extra = [];

  const set = (open) => {
    rows.forEach((row) => {
      row.hidden = !open && extra.includes(row);
    });
    toggle.setAttribute('aria-expanded', String(open));
    list.toggleAttribute('data-lenis-prevent', open);
    block.dataset.prices = open ? 'open' : '';

    if (!open) list.scrollTop = 0;
  };

  const reset = () => {
    extra = rows.slice(compact.matches ? limits.compact : limits.wide);
    toggle.hidden = !extra.length;
    set(false);
  };

  const onClick = () => set(toggle.getAttribute('aria-expanded') !== 'true');

  reset();
  toggle.addEventListener('click', onClick);

  return { reset, unbind: () => toggle.removeEventListener('click', onClick) };
}
