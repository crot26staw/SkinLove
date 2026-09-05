import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { initHeroBackdrop } from './hero.js';
import { initHeroIntro } from './hero-intro.js';
import { initServices } from './services.js';
import { initAtmosphere } from './atmosphere.js';
import { initAdvantages } from './advantages.js';
import { initCta } from './cta.js';
import { initFooter } from './footer.js';
import { initSpread } from './spread.js';
import { initProcedures } from './procedures.js';
import { initSteps } from './steps.js';
import { initTasks } from './tasks.js';
import { initReviewsBlinds } from './reviews.js';
import { initHeadingFill } from './heading-fill.js';
import { initRules } from './rule.js';
import { initSmoothScroll } from '../utils/smooth-scroll.js';

gsap.registerPlugin(ScrollTrigger);

export function initAnimations() {
  const cleanups = [
    initSmoothScroll(),
    initHeroIntro(),
    initHeroBackdrop(),
    initSpread(),
    initProcedures(),
    initSteps(),
    initTasks(),
    initServices(),
    initAtmosphere(),
    initAdvantages(),
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
