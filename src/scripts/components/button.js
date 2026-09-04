import { gsap } from 'gsap';

/* Кнопка: на ховере сердце гаснет у правого края подписи и проявляется
   у левого, подпись сдвигается ему навстречу. Сердце одно: первую половину
   оно растворяется, чуть уходя наружу, потом мгновенно переносится за левый
   край и проявляется на своё место. При уходе курсора та же сцена идёт назад —
   таймлайн просто реверсится, поэтому прерванный ховер не рвётся.

   Ход сердца — ширина подписи плюс зазор, ход подписи — ширина сердца плюс
   зазор. Подпись у каждой кнопки своя, поэтому ходы считаются из реальных
   размеров при каждом старте из покоя. Без JS и при отключённых анимациях
   сердце просто перескакивает налево — см. button.css. */

/* Насколько сердце уходит наружу, пока гаснет, — в долях своей ширины. */
const DRIFT = 0.6;
const DURATION = 0.45;

export function initButtons() {
  const buttons = [...document.querySelectorAll('[data-button]')];

  if (!buttons.length) return;

  const context = gsap.context(() => {
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      const bindings = [];

      buttons.forEach((button) => {
        const label = button.querySelector('.button__label');
        const icon = button.querySelector('.button__icon');

        if (!label || !icon) return;

        button.dataset.button = 'on';

        const gap = () => parseFloat(getComputedStyle(button).columnGap) || 0;
        const size = () => icon.getBoundingClientRect();

        const drift = () => size().width * DRIFT;
        const travel = () => -(label.offsetWidth + gap());
        const half = DURATION / 2;

        const timeline = gsap
          .timeline({ paused: true, defaults: { duration: DURATION, ease: 'power2.inOut' } })
          .to(label, { x: () => size().width + gap() }, 0)
          .to(icon, { x: drift, opacity: 0, duration: half, ease: 'power2.in' }, 0)
          .fromTo(
            icon,
            { x: () => travel() - drift(), opacity: 0 },
            { x: travel, opacity: 1, duration: half, ease: 'power2.out', immediateRender: false },
            half
          );

        /* Размеры перечитываются только из покоя: посреди хода invalidate
           запомнил бы промежуточное положение как стартовое. */
        const open = () => {
          if (!timeline.progress()) timeline.invalidate();
          timeline.play();
        };
        const close = () => timeline.reverse();
        const focus = () => button.matches(':focus-visible') && open();

        button.addEventListener('mouseenter', open);
        button.addEventListener('mouseleave', close);
        button.addEventListener('focus', focus);
        button.addEventListener('blur', close);

        bindings.push(() => {
          button.removeEventListener('mouseenter', open);
          button.removeEventListener('mouseleave', close);
          button.removeEventListener('focus', focus);
          button.removeEventListener('blur', close);
          delete button.dataset.button;
          button.setAttribute('data-button', '');
        });
      });

      return () => bindings.forEach((unbind) => unbind());
    });
  });

  return () => context.revert();
}
