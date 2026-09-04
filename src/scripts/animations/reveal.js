import { BLINDS_AT, exitLength, openLength } from './timing.js';

/* Окно открытия: отрезок прокрутки, на котором элемент секции попадает
   на экран. Им пользуются сцены, привязанные к появлению элемента, —
   заливка заголовков и черта.

   Обычный элемент въезжает снизу — окно считается от его положения на экране.
   Элемент секции, подложенной под предыдущую (data-underlap), на экране уже
   стоит, но закрыт: его открывает уезд предыдущей секции или жалюзи —
   окно идёт по второй половине этого открытия и заканчивается вместе с ним.

   Возвращает границы триггера и, в его единицах, с какой точки (at)
   и какой длины (length) идёт ход. */

/* От какой до какой отметки экрана идёт ход элемента, въезжающего прокруткой. */
const ENTER_FROM = 'top 85%';
const ENTER_TO = 'top 45%';

/* Доля открытия, на которой идёт ход подложенного элемента. */
const UNCOVER_SHARE = 0.5;

export function revealWindow(section, element) {
  if (section.dataset.underlap !== 'on') {
    return { trigger: element, start: ENTER_FROM, end: ENTER_TO, at: 0, length: 1 };
  }

  /* Подложенная секция открывается уездом предыдущей; если у неё есть жалюзи,
     они начинают расходиться на BLINDS_AT уезда и открывают её до конца.
     Отсчёт — от верха секции, длины в пикселях прокрутки. От `top top`,
     а не от смещённой отметки: секция в это время сама закреплена, и отметку
     ниже верха ScrollTrigger сдвинул бы на длину её пина. */
  const hasBlinds = Boolean(section.querySelector('[data-blind]'));
  const opening = hasBlinds ? openLength() : exitLength();
  const uncovered = hasBlinds ? exitLength() * BLINDS_AT + openLength() : exitLength();
  const length = opening * UNCOVER_SHARE;

  return {
    trigger: section,
    start: 'top top',
    end: `+=${uncovered}`,
    at: uncovered - length,
    length
  };
}
