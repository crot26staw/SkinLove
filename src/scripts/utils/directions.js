/* Список направлений с превью: наведение или фокус на пункте подменяет
   картинку и зажигает его сердце; когда курсор уходит из списка,
   показывается текущий пункт (current-menu-item). Картинки пунктов
   приходят в data-image — при натяжке их отдаёт walker меню или ACF.
   Используется в меню и в подвале. */

export function bindDirections({ list, links, preview, hearts, currentClass }) {
  if (!list || !links.length) return;

  const current = () => Math.max(0, links.findIndex((link) => link.closest('.current-menu-item')));

  const show = (index) => {
    const image = links[index].dataset.image;

    if (preview && image && preview.getAttribute('src') !== image) {
      preview.setAttribute('src', image);
    }

    hearts.forEach((heart, i) => heart.classList.toggle(currentClass, i === index));
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
  const onFocusOut = (event) => {
    if (!list.contains(event.relatedTarget)) onLeave();
  };

  list.addEventListener('mouseleave', onLeave);
  list.addEventListener('focusout', onFocusOut);
  show(current());

  return () => {
    unbinds.forEach((unbind) => unbind());
    list.removeEventListener('mouseleave', onLeave);
    list.removeEventListener('focusout', onFocusOut);
  };
}
