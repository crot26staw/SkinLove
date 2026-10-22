/* Ряд с горизонтальной прокруткой можно тянуть мышью: без тачпада колесо
   листает только по вертикали, и ряд шире экрана мышью было не пролистать.
   Палец и перо браузер листает сам. Протяжка дальше THRESHOLD — это прокрутка,
   а не нажатие: клик, который за ней последует, гасится. */

const THRESHOLD = 5;

export function enableDragScroll(row) {
  let start = null;
  let dragged = false;

  const onDown = (event) => {
    if (event.pointerType !== 'mouse' || event.button !== 0 || row.scrollWidth <= row.clientWidth) return;

    start = { x: event.clientX, left: row.scrollLeft, id: event.pointerId };
    dragged = false;
  };

  const onMove = (event) => {
    if (!start) return;

    const dx = event.clientX - start.x;

    if (!dragged && Math.abs(dx) < THRESHOLD) return;

    if (!dragged) {
      dragged = true;
      row.setPointerCapture(start.id);
    }

    row.scrollLeft = start.left - dx;
  };

  const onUp = () => {
    start = null;
  };

  const onClick = (event) => {
    if (!dragged) return;

    dragged = false;
    event.preventDefault();
    event.stopPropagation();
  };

  row.addEventListener('pointerdown', onDown);
  row.addEventListener('pointermove', onMove);
  row.addEventListener('pointerup', onUp);
  row.addEventListener('pointercancel', onUp);
  row.addEventListener('click', onClick, true);
}
