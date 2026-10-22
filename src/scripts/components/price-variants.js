/* Варианты прайса в панели процедуры (женская / мужская эпиляция):
   вкладка data-price-variant показывает список data-price-variant-list
   с тем же значением, остальные прячет атрибутом hidden. Блок прайса
   получает событие prices:reset — длинный список сворачивается заново
   (components/prices.js). Вариантов и строк в них — любое число. */
export function initPriceVariants() {
  document.querySelectorAll('[data-price-variants]').forEach(setup);
}

function setup(group) {
  const block = group.closest('[data-prices]') ?? group.parentElement;
  const buttons = [...group.querySelectorAll('[data-price-variant]')];
  const lists = [...block.querySelectorAll('[data-price-variant-list]')];

  if (!buttons.length || !lists.length) return;

  const select = (value) => {
    buttons.forEach((button) => {
      button.setAttribute('aria-pressed', String(button.dataset.priceVariant === value));
    });
    lists.forEach((list) => {
      list.hidden = list.dataset.priceVariantList !== value;
    });
    block.dispatchEvent(new CustomEvent('prices:reset'));
  };

  group.addEventListener('click', (event) => {
    const button = event.target.closest('[data-price-variant]');

    if (button && button.getAttribute('aria-pressed') !== 'true') select(button.dataset.priceVariant);
  });
}
