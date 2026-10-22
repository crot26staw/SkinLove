/* Сердца над пунктом «Не меняйте свои планы»: их столько, сколько пунктов,
   залито то, чей номер у пункта. Число пунктов задаёт редактор (Repeater),
   поэтому сердца ставит скрипт, а разметка пунктов одинакова. */

const HEART = 'M5 9.175 4.275 8.515C1.7 6.18 0 4.635 0 2.75 0 1.205 1.21 0 2.75 0c.87 0 1.705.405 2.25 1.04C5.545.405 6.38 0 7.25 0 8.79 0 10 1.205 10 2.75c0 1.885-1.7 3.43-4.275 5.765L5 9.175Z';
const SVG = 'http://www.w3.org/2000/svg';

export function initReasons() {
  document.querySelectorAll('[data-reasons]').forEach(setup);
}

function setup(section) {
  const marks = [...section.querySelectorAll('[data-reason-marks]')];

  marks.forEach((holder, index) => {
    holder.replaceChildren(
      ...marks.map((_, position) => heart(position === index))
    );
  });
}

function heart(current) {
  const svg = document.createElementNS(SVG, 'svg');
  const path = document.createElementNS(SVG, 'path');

  svg.setAttribute('class', current ? 'reason__mark reason__mark--current' : 'reason__mark');
  svg.setAttribute('viewBox', '0 0 10 9.175');
  svg.setAttribute('fill', 'none');
  svg.setAttribute('focusable', 'false');
  path.setAttribute('d', HEART);
  path.setAttribute('fill', 'currentColor');
  svg.append(path);

  return svg;
}
