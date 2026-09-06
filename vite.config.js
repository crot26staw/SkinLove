import { defineConfig } from 'vite';
import { resolve } from 'node:path';

export default defineConfig({
  // На GitHub Pages сайт живёт по адресу /SkinLove/, локально — в корне
  base: process.env.GITHUB_ACTIONS ? '/SkinLove/' : '/',
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
        ivTherapy: resolve(import.meta.dirname, 'iv-therapy.html'),
        outcall: resolve(import.meta.dirname, 'outcall.html'),
        wellness: resolve(import.meta.dirname, 'wellness.html')
      }
    }
  }
});
