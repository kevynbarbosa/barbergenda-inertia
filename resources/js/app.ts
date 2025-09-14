import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { Modal, ModalLink, renderApp } from '@inertiaui/modal-vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp } from 'vue';
import { initializeTheme } from './composables/useAppearance';
import AppLayout from './layouts/AppLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => {
        const page = resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue'));
        page.then((module) => {
            // Don't apply default layout to auth pages and Welcome page
            if (!name.startsWith('auth/') && name !== 'Welcome') {
                module.default.layout = module.default.layout || ((h, page) => h(AppLayout, () => page));
            }
        });
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: renderApp(App, props) })
            .use(plugin)
            .component('Modal', Modal)
            .component('ModalLink', ModalLink)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
