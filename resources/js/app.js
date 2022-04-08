require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const ucfirst = str => str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
const ucwords = (str, sep = ' ', join = ' ') => str.split(sep).map(word => ucfirst(word)).join(join);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        let parts = name.split('::')

        if (parts.length !== 2) {
            return import(`./Pages/${name}.vue`)
        }

        return import(`%/${parts[0]}/resources/js/Pages/${parts[1]}.vue`)
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { ucfirst, ucwords, route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
