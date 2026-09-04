import { defineConfig } from 'vite';

export default defineConfig({
  server: {
    port: 5173,
    open: true
  },
  build: {
    target: 'es2022',
    cssTarget: ['chrome112', 'edge112', 'firefox117', 'safari16.5']
  }
});
