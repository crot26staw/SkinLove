/* Шапка над героем с фото: инверсия (mix-blend-mode: difference) на пёстром
   снимке делает логотип серым и нечитаемым, поэтому секция может попросить
   шапку начать без инверсии — атрибутом data-header-plain. После прокрутки
   на PLAIN_UNTIL пикселей инверсия включается обратно, как на всех страницах. */

const PLAIN_UNTIL = 100;

/* Шапка с data-header-stuck (строка выездных услуг) после первых
   STICK_FROM пикселей прокрутки получает класс header--stuck — фон. */
const STICK_FROM = 10;

export function initHeader() {
  const header = document.querySelector('[data-header]');

  if (!header) return;

  if (header.hasAttribute('data-header-stuck')) bindStuck(header);

  const plainHero = document.querySelector('[data-header-plain]');

  if (!plainHero) return;

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

function bindStuck(header) {
  const update = () => header.classList.toggle('header--stuck', window.scrollY > STICK_FROM);

  update();
  window.addEventListener('scroll', update, { passive: true });
}
