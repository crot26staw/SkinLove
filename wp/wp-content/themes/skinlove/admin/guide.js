/* Вкладки инструкции: выбранная вкладка — в адресе (#pages), чтобы на неё
   можно было дать ссылку и вернуться после перезагрузки. */

(() => {
  const root = document.querySelector('[data-sl-guide]');

  if (!root) {
    return;
  }

  const tabs = [...root.querySelectorAll('[data-sl-guide-tab]')];
  const panels = [...root.querySelectorAll('[data-sl-guide-panel]')];
  const keys = tabs.map((tab) => tab.dataset.slGuideTab);

  const select = (key, { focus = false, scroll = false } = {}) => {
    const current = keys.includes(key) ? key : keys[0];

    tabs.forEach((tab) => {
      const active = tab.dataset.slGuideTab === current;
      tab.setAttribute('aria-selected', String(active));
      tab.tabIndex = active ? 0 : -1;
      if (active && focus) {
        tab.focus();
      }
    });

    panels.forEach((panel) => {
      panel.hidden = panel.dataset.slGuidePanel !== current;
    });

    if (scroll) {
      root.scrollIntoView({ block: 'start' });
    }

    return current;
  };

  const go = (key, options) => {
    const current = select(key, options);
    history.replaceState(null, '', `#${current}`);
  };

  root.classList.add('is-ready');
  select(location.hash.slice(1));

  tabs.forEach((tab) => {
    tab.addEventListener('click', (event) => {
      event.preventDefault();
      go(tab.dataset.slGuideTab);
    });

    tab.addEventListener('keydown', (event) => {
      const index = tabs.indexOf(tab);
      const step = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 }[event.key];
      let next = null;

      if (step) {
        next = (index + step + tabs.length) % tabs.length;
      } else if (event.key === 'Home') {
        next = 0;
      } else if (event.key === 'End') {
        next = tabs.length - 1;
      }

      if (next !== null) {
        event.preventDefault();
        go(keys[next], { focus: true });
      }
    });
  });

  // Ссылки на другие вкладки внутри текстов: <a href="#media">
  root.addEventListener('click', (event) => {
    const link = event.target.closest('.sl-guide__panels a[href^="#"]');

    if (link && keys.includes(link.hash.slice(1))) {
      event.preventDefault();
      go(link.hash.slice(1), { scroll: true });
    }
  });

  window.addEventListener('hashchange', () => select(location.hash.slice(1)));
})();
