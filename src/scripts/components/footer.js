import { bindDirections } from '../utils/directions.js';

/* Подвал: список направлений с превью, как в меню. */

export function initFooter() {
  const footers = document.querySelectorAll('[data-footer]');

  const unbinds = [...footers].map((footer) =>
    bindDirections({
      list: footer.querySelector('[data-footer-directions]'),
      links: [...footer.querySelectorAll('[data-footer-direction]')],
      preview: footer.querySelector('[data-footer-preview]'),
      hearts: [...footer.querySelectorAll('[data-footer-heart]')],
      currentClass: 'footer__heart--current',
    }),
  );

  return () => unbinds.forEach((unbind) => unbind?.());
}
