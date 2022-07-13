import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const ucfirst = str => str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
const ucwords = (str, sep = ' ', join = ' ') => str.split(sep).map(word => ucfirst(word)).join(join);
const reNumber = $event => ~~$event.target.value ? false : $event.target.select();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        console.log(name)
        let parts = name.split('::')

        if (parts.length !== 2) {
            return resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue')
            )
        }

        return resolvePageComponent(
            `/system/${parts[0]}/resources/js/Pages/${parts[1]}.vue`,
            import.meta.glob('/system/**/**/resources/js/Pages/**/*.vue')
        )
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mixin({ methods: { ucfirst, ucwords, route, reNumber } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
