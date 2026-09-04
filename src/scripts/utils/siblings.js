/* Соседняя секция с учётом распорок. Закрепляя секцию, GSAP оборачивает её
   в .pin-spacer, и соседом по DOM становится распорка, а не секция; сама
   закреплённая секция тоже видит соседей не своих, а распорки. Сцены, которые
   смотрят на соседей (подкладка, цвет жалюзи, слой перекраса), ходят сюда. */
const host = (element) =>
  element?.parentElement?.classList.contains('pin-spacer') ? element.parentElement : element;

const unwrap = (element) =>
  element?.classList.contains('pin-spacer') ? element.firstElementChild : element;

export const nextSection = (element) => unwrap(host(element)?.nextElementSibling) ?? null;

export const previousSection = (element) => unwrap(host(element)?.previousElementSibling) ?? null;
