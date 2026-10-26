/* Горизонтальная прокрутка внутри страницы под нормализатором ScrollTrigger
   (ряд отзывов ниже 1024). Нормализатор перехватывает касания на всей
   странице и гасит их preventDefault, а его allowNestedScroll отдаёт браузеру
   весь жест над прокручиваемым блоком, не глядя на ось, — по вертикали же
   браузер там листать не может (нормализатор держит на <html>
   touch-action: pan-x), и страница над рядом вставала. Разрешить pan-y
   нельзя: на iOS нормализатор каждый второй touchmove пропускает без
   preventDefault и полагается на touch-action, иначе страница поедет дважды.

   Поэтому ось решается здесь, по первому сдвигу пальца, как в Observer
   с lockAxis, но с запасом в пользу горизонтали (HORIZONTAL_BIAS): ряд
   невысокий, и палец по нему почти всегда идёт чуть наискосок — при строгом
   dx > dy такой жест уходил странице, и ряд не двигался. Горизонтальный
   жест над блоком с overflow-x прячется от нормализатора (stopPropagation
   на захвате у window — раньше любых его слушателей), и браузер листает
   блок нативно; вертикальный уходит нормализатору как обычно. Слушатели passive: preventDefault здесь не нужен.

   Обратная сторона: сдвигов пальца нормализатор не видел, и отпускание после
   свайпа принимает за тап — через 80 мс сам вызывает click() по элементу
   под пальцем (Observer, «safety valve» для мобильного Safari). Вкладка
   под пальцем выбиралась, ряд прыгал к ней. Поэтому после горизонтального
   жеста ближайший клик в пределах CLICK_GUARD гасится на захвате. */
const HORIZONTAL_BIAS = 1.5;
const CLICK_GUARD = 400;

const scrollsX = (node) => {
  const { overflowX } = getComputedStyle(node);

  return (overflowX === 'auto' || overflowX === 'scroll') && node.scrollWidth > node.clientWidth;
};

const horizontalHost = (target) => {
  let node = target instanceof Element ? target : target?.parentElement;

  while (node && node !== document.body) {
    if (scrollsX(node)) return node;

    node = node.parentElement;
  }

  return null;
};

export function allowHorizontalTouchScroll() {
  let start = null;
  let horizontal = false;
  let guardTimer = 0;

  const swallowClick = (event) => {
    event.preventDefault();
    event.stopPropagation();
    releaseGuard();
  };

  const releaseGuard = () => {
    clearTimeout(guardTimer);
    window.removeEventListener('click', swallowClick, true);
  };

  const guardClick = () => {
    releaseGuard();
    window.addEventListener('click', swallowClick, true);
    guardTimer = setTimeout(releaseGuard, CLICK_GUARD);
  };

  const onStart = (event) => {
    const touch = event.touches[0];

    horizontal = false;
    start =
      event.touches.length === 1 && horizontalHost(event.target)
        ? { x: touch.clientX, y: touch.clientY }
        : null;
  };

  const onMove = (event) => {
    if (!start) return;

    if (!horizontal) {
      const touch = event.touches[0];
      const dx = Math.abs(touch.clientX - start.x);
      const dy = Math.abs(touch.clientY - start.y);

      if (!dx && !dy) return;

      horizontal = dx * HORIZONTAL_BIAS > dy;

      /* Вертикальный жест: дальше им занимается нормализатор. */
      if (!horizontal) start = null;
    }

    if (horizontal) event.stopPropagation();
  };

  const onEnd = () => {
    if (horizontal) guardClick();

    start = null;
    horizontal = false;
  };

  const options = { capture: true, passive: true };

  window.addEventListener('touchstart', onStart, options);
  window.addEventListener('touchmove', onMove, options);
  window.addEventListener('touchend', onEnd, options);
  window.addEventListener('touchcancel', onEnd, options);

  return () => {
    releaseGuard();
    window.removeEventListener('touchstart', onStart, options);
    window.removeEventListener('touchmove', onMove, options);
    window.removeEventListener('touchend', onEnd, options);
    window.removeEventListener('touchcancel', onEnd, options);
  };
}
