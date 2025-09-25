import { defineConfig } from '@vite-pwa/assets-generator/config';
import { customPreset } from './pwa-assets-preset';

export default defineConfig({
    headLinkOptions: {
        preset: '2023',
    },
    customPreset,
    images: ['public/pwa/logo.svg'],
});
