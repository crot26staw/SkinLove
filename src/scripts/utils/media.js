/* Брейкпоинты сцен — те же, что в CSS (base/variables.css): десктоп от 1024,
   ниже — планшетная раскладка. Сцены со стыками (уезд, подкладка, стопка,
   раскрытие, сборка) живут только на десктопе; лента и жалюзи — везде. */
export const DESKTOP = '(width >= 1024px)';
export const TABLET = '(width < 1024px)';
export const MOBILE = '(width < 768px)';
export const MOTION = '(prefers-reduced-motion: no-preference)';

/* Тач-устройство — тем же запросом, которым его узнаёт ScrollTrigger.isTouch. */
export const TOUCH = '(hover: none), (pointer: coarse)';
