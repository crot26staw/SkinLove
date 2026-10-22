import './styles/main.css';

import { initButtons } from './scripts/components/button.js';
import { initHeader } from './scripts/components/header.js';
import { initMenu } from './scripts/components/menu.js';
import { initFooter } from './scripts/components/footer.js';
import { initModals } from './scripts/components/modal.js';
import { initForms } from './scripts/components/form.js';
import { initReviews } from './scripts/components/reviews.js';
import { initFaq } from './scripts/components/faq.js';
import { initResults } from './scripts/components/results.js';
import { initPrograms } from './scripts/components/programs.js';
import { initPromos } from './scripts/components/promos.js';
import { initLangSwitch } from './scripts/components/lang-switch.js';
import { initAnimations } from './scripts/animations/index.js';

initButtons();
initHeader();
initLangSwitch();
initMenu();
initFooter();
initModals();
initForms();
initReviews();
initFaq();
initResults();
initPrograms();
initPromos();
initAnimations();
