/* Шапка над героем с фото: инверсия (mix-blend-mode: difference) на пёстром
   снимке делает логотип серым и нечитаемым, поэтому секция может попросить
   шапку начать без инверсии — атрибутом data-header-plain. После прокрутки
   на PLAIN_UNTIL пикселей инверсия включается обратно, как на всех страницах. */

const PLAIN_UNTIL = 100;

export function initHeader() {
  const header = document.querySelector('[data-header]');
  const plainHero = document.querySelector('[data-header-plain]');

  if (!header || !plainHero) return;

  let plain = null;

  const update = () => {
    const next = window.scrollY <= PLAIN_UNTIL;
    if (next === plain) return;
    plain = next;
    header.classList.toggle('header--plain', plain);
  };

  update();
  window.addEventListener('scroll', update, { passive: true });
}
