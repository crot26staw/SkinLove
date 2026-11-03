/* Видео ниже первого экрана (data-lazy-video): в разметке preload="none" и
   постер, сам ролик начинает качаться, когда до окна остаётся три экрана.
   Иначе тяжёлые ролики со скролл-сцен тянутся вместе с первым экраном
   и отнимают у него канал. Запас нужен видео, идущему от прокрутки: файл
   должен прийти целиком раньше, чем его начнут перематывать. С запасом
   в экран на 20 Мбит/с и быстрой прокрутке ролик докачивался впритык. */
const AHEAD = '300% 0px';

export function initLazyVideos() {
  const videos = [...document.querySelectorAll('[data-lazy-video]')];

  if (!videos.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(({ target, isIntersecting }) => {
      if (!isIntersecting) return;

      observer.unobserve(target);
      target.preload = 'auto';
      target.load();
    });
  }, { rootMargin: AHEAD });

  videos.forEach((video) => observer.observe(video));

  return () => observer.disconnect();
}
