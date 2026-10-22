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

/* Списков в блоке может быть несколько (варианты прайса, components/
   price-variants.js) — лимит считается по видимому, при смене варианта
   блок получает событие prices:reset и сворачивается под новый список. */
function setup(block, compact, compactLimit) {
  const lists = [...block.querySelectorAll('[data-prices-list]')];
  const toggle = block.querySelector('[data-prices-toggle]');
  const limits = { wide: Number(block.dataset.pricesLimit), compact: compactLimit };

  if (!lists.length || !toggle || !limits.wide || !limits.compact) return;

  let list = lists[0];
  let extra = [];

  const set = (open) => {
    [...list.children].forEach((row) => {
      row.hidden = !open && extra.includes(row);
    });
    toggle.setAttribute('aria-expanded', String(open));
    list.toggleAttribute('data-lenis-prevent', open);
    block.dataset.prices = open ? 'open' : '';

    if (!open) list.scrollTop = 0;
  };

  const reset = () => {
    list.removeAttribute('data-lenis-prevent');
    list = lists.find((item) => !item.hidden) ?? lists[0];
    extra = [...list.children].slice(compact.matches ? limits.compact : limits.wide);
    toggle.hidden = !extra.length;
    set(false);
  };

  const onClick = () => set(toggle.getAttribute('aria-expanded') !== 'true');

  reset();
  toggle.addEventListener('click', onClick);
  block.addEventListener('prices:reset', reset);

  return {
    reset,
    unbind: () => {
      toggle.removeEventListener('click', onClick);
      block.removeEventListener('prices:reset', reset);
    }
  };
}
