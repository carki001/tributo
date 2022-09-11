import "./bootstrap";
import "vanilla-cookieconsent/dist/cookieconsent.css";

import Vue from "vue";
import Vuelidate from "vuelidate";
import VueRouter from "vue-router";
import VueI18n from "vue-i18n";
import VueLazyload from "vue-lazyload";
import bootstrap from "bootstrap";

window.Vue = Vue;
window.select2 = require("vanilla-cookieconsent");

Vue.use(VueRouter);
Vue.use(VueI18n);
Vue.use(Vuelidate);

Vue.use(VueLazyload, {
    preLoad: 1.3,
    error: "/images/error.webp",
    loading: "/images/Spinner-1s-200px.svg",
    attempt: 1,
});

Vue.component(
    "app-container",
    require("./frontend/views/AppContainer.vue").default
);

import Home from "./frontend/views/Home.vue";
import SectionDetail from "./frontend/views/SectionDetail.vue";

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
    },
    {
        path: "/section/:sectionAlias",
        name: "sectionDetail",
        component: SectionDetail,
    },
];

const router = new VueRouter({
    mode: "history",
    routes,
});

function loadLocalMessages() {
    const locales = require.context(
        "../../src/locales/frontend",
        true,
        /[A-za-z0-9-_,\s]+\.json$/i
    );
    const messages = {};

    locales.keys().forEach((key) => {
        const matched = key.match(/([A-za-z0-9-_]+)\./i);
        if (matched && matched.length > 1) {
            const locale = matched[1];
            messages[locale] = locales(key);
        }
    });

    return messages;
}

const i18n = new VueI18n({
    locale: process.env.VUE_APP_I18N_LOCALE || "es", //Set locale
    fallbackLocale: process.env.VUE_APP_I18N_FALLBACK_LOCALE || "en", //Set fallback locale
    messages: loadLocalMessages(), //Set locale messages
});

const opts = {
    lang: {
        t: (key, ...params) => i18n.t(key, params),
    },
};

const app = new Vue({
    el: "#app",
    i18n,
    router,
});
