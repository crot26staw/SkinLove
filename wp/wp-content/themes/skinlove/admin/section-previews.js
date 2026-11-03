/**
 * Меню «Добавить секцию»: заголовки групп и превью секции при наведении
 * или фокусе на пункт. Меню рисует React Carbon Fields — свои узлы в него
 * не вставляем, только атрибуты на его <li>; превью живёт отдельно в <body>.
 */
(() => {
  const sections = window.skinloveSections;

  if (!sections) {
    return;
  }

  const ITEM = '.cf-complex__inserter-item';
  const preview = document.createElement('figure');
  const image = document.createElement('img');

  preview.className = 'sl-section-preview';
  preview.hidden = true;
  image.alt = '';
  preview.append(image);
  document.body.append(preview);

  // Размечаем только меню конструктора секций: у вложенных complex
  // (прайс, пункты) подписи пунктов с секциями не совпадают.
  const prepare = (menu) => {
    const items = [...menu.querySelectorAll(ITEM)];

    if (menu.dataset.slSections || !items.every((item) => sections[item.textContent])) {
      return;
    }

    menu.dataset.slSections = 'true';
    menu.setAttribute('role', 'menu');

    items.forEach((item) => {
      const { group } = sections[item.textContent];

      item.tabIndex = 0;
      item.setAttribute('role', 'menuitem');

      if (group) {
        item.dataset.slGroup = group;
      }
    });
  };

  const show = (item) => {
    const menu = item.closest('[data-sl-sections]');
    const src = menu && sections[item.textContent].preview;

    if (!src) {
      preview.hidden = true;
      return;
    }

    const gap = 12;
    const menuRect = menu.getBoundingClientRect();
    const itemRect = item.getBoundingClientRect();

    image.src = src;
    preview.hidden = false;

    // Справа от меню, а если не влезает — слева; по высоте — у пункта,
    // но в пределах окна.
    const { width, height } = preview.getBoundingClientRect();
    const right = menuRect.right + gap;
    const left = right + width <= window.innerWidth - gap ? right : Math.max(gap, menuRect.left - width - gap);
    const top = Math.min(Math.max(gap, itemRect.top - height / 3), window.innerHeight - height - gap);

    preview.style.left = `${left}px`;
    preview.style.top = `${Math.max(gap, top)}px`;
  };

  // После загрузки картинки и при прокрутке превью встаёт заново
  // у пункта под курсором или в фокусе.
  const follow = () => {
    const item = document.querySelector(`[data-sl-sections]:not([hidden]) ${ITEM}:is(:hover, :focus)`);

    if (item) {
      show(item);
    } else {
      preview.hidden = true;
    }
  };

  image.addEventListener('load', follow);
  window.addEventListener('scroll', follow, { passive: true });

  document.addEventListener('click', (event) => {
    const button = event.target.closest('.cf-complex__inserter-button');
    const menu = button && button.parentElement.querySelector('.cf-complex__inserter-menu');

    if (menu) {
      prepare(menu);
    }

    preview.hidden = true;
  }, true);

  document.addEventListener('mouseover', (event) => {
    const item = event.target.closest(ITEM);

    if (item) {
      show(item);
    } else if (!preview.hidden && !event.target.closest('.cf-complex__inserter-menu')) {
      preview.hidden = true;
    }
  });

  document.addEventListener('focusin', (event) => {
    const item = event.target.closest(ITEM);

    if (item) {
      show(item);
    }
  });

  document.addEventListener('keydown', (event) => {
    const item = event.target.closest && event.target.closest('[data-sl-sections] ' + ITEM);

    if (item && (event.key === 'Enter' || event.key === ' ')) {
      event.preventDefault();
      item.click();
      preview.hidden = true;
    }
  });
})();
