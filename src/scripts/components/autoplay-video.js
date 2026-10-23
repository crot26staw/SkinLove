import { MOTION } from '../utils/media.js';

/* Фоновое видео без панели (data-autoplay-video): играет по кругу, пока
   видно на экране, за кадром стоит на паузе — не тратит батарею и сеть.
   При «уменьшить движение» не играет вовсе: остаётся постер. Атрибут
   autoplay в разметке — запасной вариант на случай, если скрипт не загрузился. */
export function initAutoplayVideos() {
  const videos = [...document.querySelectorAll('[data-autoplay-video]')];

  if (!videos.length) return;

  const motion = window.matchMedia(MOTION);
  const visible = new Set();

  const update = (video) => {
    if (motion.matches && visible.has(video)) {
      video.play().catch(() => {});
    } else {
      video.pause();
    }
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(({ target, isIntersecting }) => {
      if (isIntersecting) visible.add(target);
      else visible.delete(target);
      update(target);
    });
  });

  const onMotionChange = () => videos.forEach(update);

  videos.forEach((video) => {
    video.pause();
    observer.observe(video);
  });

  motion.addEventListener('change', onMotionChange);

  return () => {
    observer.disconnect();
    motion.removeEventListener('change', onMotionChange);
  };
}
