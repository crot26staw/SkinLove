/* Перемотка видео от прокрутки: seek(progress) ставит кадр по доле от 0 до 1.

   Кадр ставится не на каждый тик прокрутки, а по одному: следующая перемотка
   уходит только когда предыдущая отрисована. Иначе перемотки копятся,
   браузер бросает промежуточные, и на ходу видео стоит, а кадр
   допрыгивает уже после остановки. */
export function createVideoScrub(video) {
  let target = 0;
  let pending = false;

  const apply = () => {
    if (video.readyState < HTMLMediaElement.HAVE_METADATA || video.seeking) {
      pending = true;
      return;
    }

    pending = false;

    const time = target * video.duration;
    if (Math.abs(video.currentTime - time) > 1 / 60) video.currentTime = time;
  };

  const onSeeked = () => pending && apply();

  video.addEventListener('seeked', onSeeked);
  video.addEventListener('loadedmetadata', apply, { once: true });

  return {
    seek(progress) {
      target = progress;
      apply();
    },
    destroy() {
      video.removeEventListener('seeked', onSeeked);
      video.removeEventListener('loadedmetadata', apply);
    }
  };
}
