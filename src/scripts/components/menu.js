import { gsap } from 'gsap';

import { lockScroll } from '../utils/smooth-scroll.js';

/* Меню: кнопка в шапке открывает оверлей под ней. Пока меню открыто,
   прокрутка страницы остановлена, а контент под оверлеем недоступен
   для фокуса (inert). Escape закрывает, фокус возвращается на кнопку.

   Список направлений: наведение или фокус на пункте подменяет превью
   и зажигает его сердце; когда курсор уходит из списка, показывается
   текущий пункт (current-menu-item). Картинки пунктов приходят
   в data-image — при натяжке их отдаёт walker меню или ACF. */

const DURATION = 0.4;

export function initMenu() {
  const toggle = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-menu]');

  if (!toggle || !menu) return;

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const covered = [...document.querySelectorAll('main, footer')];
  let tween = null;

  const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';

  const open = () => {
    tween?.kill();
    toggle.setAttribute('aria-expanded', 'true');
    menu.hidden = false;
    covered.forEach((element) => {
      element.inert = true;
    });
    lockScroll(true);

    if (!reduceMotion.matches) {
      tween = gsap.fromTo(menu, { opacity: 0 }, { opacity: 1, duration: DURATION, ease: 'power2.out' });
    }

    menu.querySelector('a')?.focus({ preventScroll: true });
  };

  const close = ({ restoreFocus = true } = {}) => {
    tween?.kill();
    toggle.setAttribute('aria-expanded', 'false');

    const finish = () => {
      menu.hidden = true;
      gsap.set(menu, { clearProps: 'opacity' });
      covered.forEach((element) => {
        element.inert = false;
      });
      lockScroll(false);
      if (restoreFocus) toggle.focus({ preventScroll: true });
    };

    if (reduceMotion.matches) {
      finish();
    } else {
      tween = gsap.to(menu, { opacity: 0, duration: DURATION * 0.75, ease: 'power2.in', onComplete: finish });
    }
  };

  const onToggle = () => (isOpen() ? close() : open());
  const onKeydown = (event) => {
    if (event.key === 'Escape' && isOpen()) close();
  };

  toggle.addEventListener('click', onToggle);
  document.addEventListener('keydown', onKeydown);

  const unbindDirections = setupDirections(menu);

  return () => {
    if (isOpen()) close({ restoreFocus: false });
    toggle.removeEventListener('click', onToggle);
    document.removeEventListener('keydown', onKeydown);
    unbindDirections?.();
  };
}

function setupDirections(menu) {
  const list = menu.querySelector('[data-menu-directions]');
  const links = list ? [...list.querySelectorAll('[data-menu-direction]')] : [];
  const preview = menu.querySelector('[data-menu-preview]');
  const hearts = [...menu.querySelectorAll('[data-menu-heart]')];

  if (!list || !links.length) return;

  const current = () => Math.max(0, links.findIndex((link) => link.closest('.current-menu-item')));

  const show = (index) => {
    const image = links[index].dataset.image;

    if (preview && image && preview.getAttribute('src') !== image) {
      preview.setAttribute('src', image);
    }

    hearts.forEach((heart, i) => heart.classList.toggle('site-menu__heart--current', i === index));
  };

  const unbinds = links.map((link, index) => {
    const onEnter = () => show(index);

    link.addEventListener('mouseenter', onEnter);
    link.addEventListener('focus', onEnter);

    return () => {
      link.removeEventListener('mouseenter', onEnter);
      link.removeEventListener('focus', onEnter);
    };
  });

  const onLeave = () => show(current());

  list.addEventListener('mouseleave', onLeave);
  list.addEventListener('focusout', (event) => {
    if (!list.contains(event.relatedTarget)) onLeave();
  });
  show(current());

  return () => {
    unbinds.forEach((unbind) => unbind());
    list.removeEventListener('mouseleave', onLeave);
  };
}
