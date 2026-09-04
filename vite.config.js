import { defineConfig } from 'vite';

export default defineConfig({
  // На GitHub Pages сайт живёт по адресу /SkinLove/, локально — в корне
  base: process.env.GITHUB_ACTIONS ? '/SkinLove/' : '/',
  server: {
    port: 5173,
    open: true
  },
  build: {
    target: 'es2022',
    cssTarget: ['chrome112', 'edge112', 'firefox117', 'safari16.5']
  }
});
