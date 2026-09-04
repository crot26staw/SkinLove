import { gsap } from 'gsap';

import { screen } from './timing.js';

/* Сцена секции: секция закрепляется, маленький кадр в середине карточки
   разрастается во всю её площадь, разбросанные по ширине слова заголовка
   съезжаются в строку и светлеют на потемневшем снимке, следом проявляются
   текст и кнопка.

   Рост кадра сделан как в «Раскрытии»: окно открывается через clip-path,
   а картинка внутри масштабируется — так снимок не растягивается и не
   дёргается layout. Разброс слов — через x, посчитанный от их собственной
   раскладки, поэтому строка собирается ровно в ту, что стоит в разметке.

   Паузы в конце нет намеренно: сборка заканчивается ровно на снятии с
   закрепления, и собранный блок видно, пока секция уезжает вверх, — а жалюзи
   подвала в это же время расходятся под ней. Пауза здесь читалась бы как
   зависание: экран стоит, а страница прокручивается. */

/* Стартовая геометрия из макета: карточка 1840×880, окно кадра 642×500
   со скруглением 98. Держим долями от карточки, чтобы сцена считалась
   от её реальных размеров. */
const PREVIEW_WIDTH = 642 / 1840;
const PREVIEW_HEIGHT = 500 / 880;
const PREVIEW_RADIUS = 98 / 1840;

/* Снимок вертикальный, и кадрируется он в двух состояниях по-разному: в полном
   кадре видна нижняя треть (её задаёт CSS), в маленьком — общий план из
   середины. Доля отсчитывается от того, что не влезло в окно. */
const PREVIEW_CROP = 0.39;

export function initCta() {
  const section = document.querySelector('[data-section="cta"]');
  const card = section?.querySelector('[data-cta-card]');
  const media = section?.querySelector('[data-cta-media]');
  const image = section?.querySelector('[data-cta-image]');
  const shade = section?.querySelector('[data-cta-shade]');
  const title = section?.querySelector('[data-cta-title]');
  const words = title ? [...title.querySelectorAll('[data-cta-word]')] : [];
  const aside = section ? [...section.querySelectorAll('[data-cta-aside]')] : [];

  if (!section || !card || !media || !image || !shade || !title || !words.length) return;

  const morph = () => screen() * 1.1;

  const radius = (element) => parseFloat(getComputedStyle(element).borderTopLeftRadius) || 0;
  const token = (name) => getComputedStyle(section).getPropertyValue(name).trim();

  const windowWidth = () => card.offsetWidth * PREVIEW_WIDTH;
  const windowHeight = () => card.offsetHeight * PREVIEW_HEIGHT;
  const windowTop = () => (card.offsetHeight - windowHeight()) / 2;

  /* Все четыре стороны выписаны и в закрытом, и в открытом виде: GSAP считает
     строку по числам подряд, и запись `inset(0px …)` разошлась бы с четырьмя. */
  const mediaClosed = () => {
    const side = (card.offsetWidth - windowWidth()) / 2;
    const top = windowTop();
    const bottom = card.offsetHeight - top - windowHeight();

    return `inset(${top}px ${side}px ${bottom}px ${side}px` +
      ` round ${card.offsetWidth * PREVIEW_RADIUS}px)`;
  };
  const mediaOpened = () => `inset(0px 0px 0px 0px round ${radius(media)}px)`;

  /* Картинка сжимается ровно до ширины окна: тогда в маленьком кадре виден
     широкий план, а не увеличенный кусок финального. Отсчёт от её собственного
     положения в CSS, чтобы финалом сцены была чистая единица трансформации. */
  const imageY = () => {
    const scaled = image.offsetHeight * PREVIEW_WIDTH;
    const top = windowTop() - PREVIEW_CROP * (scaled - windowHeight());

    return top - image.offsetTop;
  };

  /* Разброс — это та же строка, разложенная по ширине карточки с равными
     промежутками. Считаем целевые сдвиги от текущих позиций слов. */
  const spread = () => {
    const taken = words.reduce((sum, word) => sum + word.offsetWidth, 0);
    const gap = (title.clientWidth - taken) / (words.length - 1);
    let left = 0;

    return words.map((word) => {
      const shift = left - word.offsetLeft;
      left += word.offsetWidth + gap;

      return shift;
    });
  };

  /* В разбросанном виде строка стоит одна и держится по центру карточки.
     В собранном её поднимают появившиеся текст и кнопка. */
  const titleY = () =>
    card.offsetHeight / 2 - (title.offsetTop + title.offsetHeight / 2);

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      section.dataset.scene = 'on';

      const length = morph();

      const timeline = gsap.timeline({
        defaults: { ease: 'none' },
        scrollTrigger: {
          trigger: section,
          start: 'top top',
          end: () => `+=${morph()}`,
          pin: true,
          scrub: true,
          invalidateOnRefresh: true
        }
      });

      timeline
        .fromTo(
          media,
          { clipPath: () => mediaClosed() },
          { clipPath: () => mediaOpened(), duration: length * 0.6 },
          0
        )
        .fromTo(
          image,
          { scale: PREVIEW_WIDTH, y: () => imageY(), transformOrigin: '50% 0' },
          { scale: 1, y: 0, duration: length * 0.6 },
          0
        )
        .fromTo(shade, { opacity: 0 }, { opacity: 1, duration: length * 0.45 }, length * 0.1)
        .fromTo(
          words,
          { x: (index) => spread()[index] },
          { x: 0, duration: length * 0.55 },
          length * 0.1
        )
        .fromTo(title, { y: () => titleY() }, { y: 0, duration: length * 0.55 }, length * 0.1)
        /* Слова светлеют, когда снимок под ними уже потемнел, — иначе строка
           на пару кадров пропадает на светлом фоне. */
        .fromTo(
          title,
          { color: () => token('--color-ink') },
          { color: () => token('--color-bg'), duration: length * 0.25 },
          length * 0.4
        )
        .fromTo(aside, { opacity: 0 }, { opacity: 1, duration: length * 0.35 }, length * 0.65);

      return () => delete section.dataset.scene;
    });
  }, section);

  return () => context.revert();
}
