import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { initHeroBackdrop } from './hero.js';
import { initAboutDissolve } from './about.js';
import { initServices } from './services.js';
import { initAtmosphere } from './atmosphere.js';
import { initAdvantages } from './advantages.js';
import { initCta } from './cta.js';
import { initFooter } from './footer.js';
import { initSmoothScroll } from '../utils/smooth-scroll.js';

gsap.registerPlugin(ScrollTrigger);

export function initAnimations() {
  const cleanups = [
    initSmoothScroll(),
    initHeroBackdrop(),
    initAboutDissolve(),
    initServices(),
    initAtmosphere(),
    initAdvantages(),
    initCta(),
    initFooter()
  ].filter(Boolean);

  window.addEventListener('load', () => ScrollTrigger.refresh(), { once: true });

  return () => cleanups.forEach((cleanup) => cleanup());
}
