import { defineConfig } from 'vite';
import { resolve } from 'node:path';

// На GitHub Pages сайт живёт по адресу /SkinLove/, локально — в корне
const base = process.env.GITHUB_ACTIONS ? '/SkinLove/' : '/';

// Vite переписывает base только в стандартных атрибутах (src, href),
// пути картинок в data-image доводим сами
const dataImageBase = {
  name: 'data-image-base',
  transformIndexHtml: (html) => html.replace(/data-image="\/(?!\/)/g, `data-image="${base}`)
};

export default defineConfig({
  base,
  plugins: [dataImageBase],
  server: {
    port: 5173,
    open: true
  },
  build: {
    target: 'es2022',
    cssTarget: ['chrome112', 'edge112', 'firefox117', 'safari16.5'],
    rollupOptions: {
      // Одна страница вёрстки — один будущий шаблон темы
      input: {
        index: resolve(import.meta.dirname, 'index.html'),
        about: resolve(import.meta.dirname, 'about.html'),
        contacts: resolve(import.meta.dirname, 'contacts.html'),
        body: resolve(import.meta.dirname, 'body.html'),
        face: resolve(import.meta.dirname, 'face.html'),
        hardware: resolve(import.meta.dirname, 'hardware.html'),
        injection: resolve(import.meta.dirname, 'injection.html'),
        ivTherapy: resolve(import.meta.dirname, 'iv-therapy.html'),
        outcall: resolve(import.meta.dirname, 'outcall.html'),
        wellness: resolve(import.meta.dirname, 'wellness.html')
      }
    }
  }
});
