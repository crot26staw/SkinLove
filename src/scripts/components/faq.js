import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

/* Аккордеон на <details>: без JS и при отключённых анимациях вопросы
   открываются нативно, скачком. С JS клик перехватывается, и тело ответа
   плавно раскрывается по высоте — это единственное место, где высота
   анимируется намеренно: ответ должен раздвигать соседей.

   Пока ответ закрывается, атрибут open ещё стоит, иначе тело схлопнулось бы
   сразу; на это время вопрос помечен data-faq-closing, чтобы кнопка уже
   выглядела закрытой.

   Раскрытый ответ меняет высоту страницы, и всё, что ниже, съезжает: без
   пересчёта закреплённые сцены под FAQ ловили бы пин по старым отметкам
   и прыгали на входе и на выходе. Поэтому после каждого хода — refresh. */
const DURATION = 0.4;

export function initFaq() {
  const items = [...document.querySelectorAll('[data-faq-item]')];

  if (!items.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const unbinds = items.map(setup).filter(Boolean);

  return () => unbinds.forEach((unbind) => unbind());
}

function setup(details) {
  const summary = details.querySelector('summary');
  const body = details.querySelector('[data-faq-body]');

  if (!summary || !body) return;

  let tween = null;

  /* Обрезка стоит только на время хода: в покое она срезала бы выносные
     элементы последней строки. */
  const start = () => gsap.set(body, { overflow: 'hidden' });
  const finish = () => {
    gsap.set(body, { clearProps: 'height,overflow' });
    ScrollTrigger.refresh();
  };

  const close = () => {
    details.dataset.faqClosing = '';
    start();
    tween = gsap.to(body, {
      height: 0,
      duration: DURATION,
      ease: 'power2.inOut',
      onComplete: () => {
        details.open = false;
        delete details.dataset.faqClosing;
        finish();
      }
    });
  };

  const open = () => {
    const wasClosing = 'faqClosing' in details.dataset;

    delete details.dataset.faqClosing;
    details.open = true;
    start();

    const vars = { height: 'auto', duration: DURATION, ease: 'power2.inOut', onComplete: finish };

    tween = wasClosing ? gsap.to(body, vars) : gsap.from(body, { ...vars, height: 0 });
  };

  const onClick = (event) => {
    event.preventDefault();
    tween?.kill();

    if (details.open && !('faqClosing' in details.dataset)) {
      close();
    } else {
      open();
    }
  };

  summary.addEventListener('click', onClick);

  return () => {
    tween?.kill();
    summary.removeEventListener('click', onClick);
    delete details.dataset.faqClosing;
    finish();
  };
}
