/* Закрепляя секцию, GSAP подменяет её в потоке распоркой .pin-spacer высотой
   в секцию плюс длину пина. У распорки нет фона, и если секция ниже экрана,
   под ней просвечивает фон страницы — светлая полоса на стыке. Распорка
   красится в цвет своей секции.

   Только для секций, под которые ничего не подложено: распорка наследует
   z-index секции и закрасила бы подложенную под неё следующую. Вызывать
   из onRefresh триггера: при его создании распорки может ещё не быть. */
export function paintSpacer(trigger) {
  const section = trigger?.pin;
  const spacer = trigger?.spacer;

  if (!section || !spacer) return;

  spacer.style.backgroundColor = getComputedStyle(section).backgroundColor;
}

/* Секция, под которую подтянута следующая (поджатие снизу), красить распорку
   не должна: распорка наследует z-index секции и закрыла бы подтянутый край.
   Снимает и краску, оставшуюся от другого брейкпоинта. */
export function unpaintSpacer(trigger) {
  trigger?.spacer?.style.removeProperty('background-color');
}
