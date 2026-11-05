import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { initHeroBackdrop } from './hero.js';
import { initHeroIntro } from './hero-intro.js';
import { initHeroVideo } from './hero-video.js';
import { initServices } from './services.js';
import { initAtmosphere } from './atmosphere.js';
import { initAdvantages } from './advantages.js';
import { initReel } from './reel.js';
import { initCta } from './cta.js';
import { initFooter } from './footer.js';
import { initSpread } from './spread.js';
import { initTour } from './tour.js';
import { initProcedures } from './procedures.js';
import { initSteps } from './steps.js';
import { initTasks } from './tasks.js';
import { initOccasions } from './occasions.js';
import { initReviewsBlinds } from './reviews.js';
import { initPromosBlinds } from './promos.js';
import { initProcess } from './process.js';
import { initHeadingFill } from './heading-fill.js';
import { initRules } from './rule.js';
import { initSmoothScroll } from '../utils/smooth-scroll.js';

gsap.registerPlugin(ScrollTrigger);

export function initAnimations() {
  const cleanups = [
    initSmoothScroll(),
    initHeroIntro(),
    initHeroBackdrop(),
    initHeroVideo(),
    /* До разъезда: отступ экскурсии сдвигает его старт. */
    initTour(),
    initSpread(),
    /* Сцены с закреплением — в порядке секций на странице: распорку
       закреплённой секции ScrollTrigger учитывает только у триггеров,
       созданных после неё. */
    initOccasions(),
    initProcedures(),
    initPromosBlinds(),
    initProcess(),
    initSteps(),
    initTasks(),
    initServices(),
    initAtmosphere(),
    initAdvantages(),
    initReel(),
    initReviewsBlinds(),
    initCta(),
    initFooter(),
    /* Последними: смотрят на data-underlap, который ставят сцены выше. */
    initHeadingFill(),
    initRules()
  ].filter(Boolean);

  window.addEventListener('load', () => ScrollTrigger.refresh(), { once: true });

  return () => cleanups.forEach((cleanup) => cleanup());
}
