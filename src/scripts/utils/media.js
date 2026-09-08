/* Брейкпоинты сцен — те же, что в CSS (base/variables.css): десктоп от 1024,
   ниже — планшетная раскладка. Сцены со стыками (уезд, подкладка, стопка,
   раскрытие, сборка) живут только на десктопе; лента и жалюзи — везде.
   TABLET_ONLY — планшет без мобильного: для сцен, которые ниже 768
   уступают место нативной прокрутке. */
export const DESKTOP = '(width >= 1024px)';
export const TABLET = '(width < 1024px)';
export const TABLET_ONLY = '(width >= 768px) and (width < 1024px)';
export const MOBILE = '(width < 768px)';
export const MOTION = '(prefers-reduced-motion: no-preference)';

/* Тач-устройство — тем же запросом, которым его узнаёт ScrollTrigger.isTouch. */
export const TOUCH = '(hover: none), (pointer: coarse)';
