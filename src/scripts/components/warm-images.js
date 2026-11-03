/* Прогрев картинок в лентах и стопках (секция с data-warm-images).
   Картинки там ленивые, чтобы не качаться вместе с первым экраном, но
   дождаться браузерной ленивой загрузки нельзя: карточки стоят за краем
   экрана, и браузер качал и декодировал их прямо посреди сцены — кадры по
   100–250мс. Поэтому за два экрана до секции загрузка запускается сама,
   а decode() раскодирует картинку заранее, не в кадре сцены. */
const AHEAD = '200% 0px';

export function initWarmImages() {
  const sections = [...document.querySelectorAll('[data-warm-images]')];

  if (!sections.length) return;

  const warm = (section) => {
    section.querySelectorAll('img[loading="lazy"]').forEach((image) => {
      image.loading = 'eager';
      image.decode().catch(() => {});
    });
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(({ target, isIntersecting }) => {
      if (!isIntersecting) return;

      observer.unobserve(target);
      warm(target);
    });
  }, { rootMargin: AHEAD });

  sections.forEach((section) => observer.observe(section));

  return () => observer.disconnect();
}
