import { gsap } from 'gsap';

import { revealWindow } from './reveal.js';

/* Заливка (fill): заголовок стоит приглушённым, и по мере прокрутки слова
   одно за другим набирают цвет. Слова режет скрипт — в разметке и в админке
   заголовок остаётся обычным текстом.

   Где на прокрутке идёт заливка, решает окно открытия (reveal.js): обычный
   заголовок наливается, въезжая снизу, подложенный — пока его открывает
   уезд предыдущей секции или жалюзи.

   Описание сцен и их имена — в ANIMATIONS.md. */

const WORD_CLASS = 'heading__word';

/* Слова идут почти встык: следующее трогается, когда предыдущее налилось
   на эту долю. */
const STAGGER = 0.6;

export function initHeadingFill() {
  const cleanups = [...document.querySelectorAll('[data-heading-fill]')].map(setup).filter(Boolean);

  if (!cleanups.length) return;

  return () => cleanups.forEach((cleanup) => cleanup());
}

function setup(heading) {
  const section = heading.closest('[data-section]');

  if (!section) return;

  const muted = () =>
    parseFloat(getComputedStyle(heading).getPropertyValue('--heading-muted')) || 0;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      const words = splitWords(heading);

      if (!words.length) return;

      const { trigger, start, end, at, length } = revealWindow(section, heading);

      const timeline = gsap.timeline({
        defaults: { ease: 'none' },
        scrollTrigger: { trigger, start, end, scrub: true, invalidateOnRefresh: true }
      });

      /* По твину на слово, а не один со stagger: у общего твина стартовое
         состояние получает только первое слово, остальные до своего хода
         остались бы залитыми. Ход одного слова — из общей длины и шага. */
      const each = length / (1 + STAGGER * (words.length - 1));

      words.forEach((word, index) => {
        timeline.fromTo(
          word,
          { opacity: muted },
          { opacity: 1, duration: each },
          at + index * each * STAGGER
        );
      });

      return () => joinWords(heading, words);
    });
  }, heading);

  return () => context.revert();
}

/* Каждое слово текста — в свой span; пробелы остаются текстовыми узлами,
   чтобы перенос строк работал как раньше. */
function splitWords(root) {
  const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
  const nodes = [];
  const words = [];

  while (walker.nextNode()) nodes.push(walker.currentNode);

  nodes.forEach((node) => {
    if (!node.nodeValue.trim()) return;

    const fragment = document.createDocumentFragment();

    node.nodeValue.split(/(\s+)/).forEach((part) => {
      if (!part) return;

      if (!part.trim()) {
        fragment.append(part);
        return;
      }

      const word = document.createElement('span');

      word.className = WORD_CLASS;
      word.textContent = part;
      words.push(word);
      fragment.append(word);
    });

    node.replaceWith(fragment);
  });

  return words;
}

function joinWords(root, words) {
  words.forEach((word) => word.replaceWith(word.textContent));
  root.normalize();
}
