import { defineConfig } from 'vite';
import { resolve } from 'node:path';
import { cpSync } from 'node:fs';

// На GitHub Pages сайт живёт по адресу /SkinLove/, локально — в корне
const base = process.env.GITHUB_ACTIONS ? '/SkinLove/' : '/';

// Vite переписывает base только в стандартных атрибутах (src, href),
// пути картинок в data-image доводим сами
const dataImageBase = {
  name: 'data-image-base',
  transformIndexHtml: (html) => html.replace(/data-image="\/(?!\/)/g, `data-image="${base}`)
};

const root = import.meta.dirname;
const target = 'es2022';
const cssTarget = ['chrome112', 'edge112', 'firefox117', 'safari16.5'];

// Декоративная графика и видео, завязанные на скролл, живут в теме,
// а не в медиабиблиотеке. Остальное из public/ — контент, его заносят сиды.
const themeStatic = [
  'favicon.svg',
  'img/logo-black.png',
  'img/logo-white.png',
  'img/alfa.svg',
  'img/wordmark-light.svg',
  'img/services-aside.png',
  'img/about-video-poster.jpg',
  'img/frame-video-poster.jpg',
  'img/outcall-reasons-poster.jpg',
  'video'
];

const copyThemeStatic = (outDir) => ({
  name: 'copy-theme-static',
  writeBundle: () => themeStatic.forEach((path) => {
    cpSync(resolve(root, 'public', path), resolve(outDir, path), { recursive: true });
  })
});

// npm run build:theme — ассеты в тему WordPress, без хешей в именах,
// чтобы functions.php подключал их по постоянным путям
const themeOutDir = resolve(root, 'wp/wp-content/themes/skinlove/assets');

const themeConfig = {
  base: './',
  publicDir: false,
  plugins: [copyThemeStatic(themeOutDir)],
  build: {
    target,
    cssTarget,
    outDir: themeOutDir,
    emptyOutDir: true,
    rollupOptions: {
      input: resolve(root, 'src/main.js'),
      output: {
        entryFileNames: 'js/main.js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: ({ names }) => (names[0]?.endsWith('.css') ? 'css/[name][extname]' : 'img/[name][extname]')
      }
    }
  }
};

export default defineConfig(({ mode }) => (mode === 'theme' ? themeConfig : {
  base,
  plugins: [dataImageBase],
  server: {
    port: 5173,
    open: true
  },
  build: {
    target,
    cssTarget,
    rollupOptions: {
      // Одна страница вёрстки — один будущий шаблон темы
      input: {
        index: resolve(root, 'index.html'),
        about: resolve(root, 'about.html'),
        contacts: resolve(root, 'contacts.html'),
        body: resolve(root, 'body.html'),
        face: resolve(root, 'face.html'),
        hardware: resolve(root, 'hardware.html'),
        injection: resolve(root, 'injection.html'),
        ivTherapy: resolve(root, 'iv-therapy.html'),
        outcall: resolve(root, 'outcall.html'),
        wellness: resolve(root, 'wellness.html')
      }
    }
  }
}));
