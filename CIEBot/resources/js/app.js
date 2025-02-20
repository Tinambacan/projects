import "./bootstrap";
import "../css/app.css";

import $ from "jquery";

window.jQuery = window.$ = $;
import { createApp, h } from "vue";
// import Main from "./Pages/LandingPage.vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faUserSecret } from "@fortawesome/free-solid-svg-icons";
import { faPlus } from "@fortawesome/free-solid-svg-icons";
import { faPaperPlane } from "@fortawesome/free-solid-svg-icons";
import { faMicrophone } from "@fortawesome/free-solid-svg-icons";
import { faVolumeHigh } from "@fortawesome/free-solid-svg-icons";
import { faCircleStop } from "@fortawesome/free-solid-svg-icons";
import { faUserCircle } from "@fortawesome/free-solid-svg-icons";



library.add(faUserCircle);
library.add(faUserSecret);
library.add(faPlus);
library.add(faPaperPlane);
library.add(faMicrophone);
library.add(faVolumeHigh);
library.add(faCircleStop);

const appName = import.meta.env.VITE_APP_NAME || "CIEBOT";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#ffffff",
    },
});
// createApp(Main)
// .component('font-awesome-icon', FontAwesomeIcon)
// .mount('#app')
