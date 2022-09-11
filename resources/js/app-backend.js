import "./bootstrap";
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/dist/vuetify.min.css";

import Vue from "vue";
import Vuex from "vuex";
import Vuetify from "vuetify";
import VueRouter from "vue-router";
import VueI18n from "vue-i18n";
import Jwt from "jsonwebtoken";

window.Vue = Vue;

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(VueI18n);
Vue.use(Vuetify);

const getDefaultState = () => {
    let token = localStorage.getItem("own-spa-token");
    let tokenData = token ? Jwt.decode(token) : null;
    return {
        isLoggedIn: false,
        tokenData: tokenData,
        profile: token ? tokenData.user.user : {},
    };
};

const store = new Vuex.Store({
    state: getDefaultState(),
    mutations: {
        authenticate(state, payload) {
            state.isLoggedIn = localStorage.getItem("own-spa-token")
                ? true
                : false;

            if (state.isLoggedIn) {
                state.profile = payload;
                let token = localStorage.getItem("own-spa-token");
                state.tokenData = token ? Jwt.decode(token) : null;
            } else {
                state.profile = {};
            }
        },
        resetState(state) {
            state.isLoggedIn = false;
            state.tokenData = null;
            state.profile = {};
        },
    },
    actions: {
        authenticate(context, payload) {
            context.commit("authenticate", payload);
        },
        resetState(context) {
            context.commit("resetState");
        },
    },
});

import AppContainer from "./backend/views/AppContainer.vue";

Vue.component("app-container", AppContainer);

import Login from "./backend/views/Login.vue";
import ResetPassword from "./backend/views/ResetPassword.vue";
import Error404 from "./backend/views/Error404.vue";
import AdminContainer from "./backend/views/AdminContainer.vue";
import Users from "./backend/views/Users.vue";
import Settings from "./backend/views/Settings.vue";
import Sections from "./backend/views/Sections.vue";
import Galleries from "./backend/views/Galleries.vue";

const routes = [
    { path: "/login", name: "login", component: Login },
    {
        path: "/obnovit-heslo",
        name: "reset-password",
        component: ResetPassword,
    },
    {
        path: "/admin",
        component: AdminContainer,
        children: [
            { path: "", redirect: "sections" },
            {
                path: "sections",
                name: "sections",
                component: Sections,
            },
            {
                path: "galleries",
                name: "galleries",
                component: Galleries,
            },
            {
                path: "users",
                name: "users",
                component: Users,
                beforeEnter(to, from, next) {
                    const token = localStorage.getItem("own-spa-token");

                    if (!token) {
                        return;
                    }
                    const tokenData = Jwt.decode(token);

                    if (tokenData.user.user.role === "admin") {
                        next();
                    } else {
                        next("/404");
                    }
                },
            },
            {
                path: "settings",
                name: "settings",
                component: Settings,
                beforeEnter(to, from, next) {
                    const token = localStorage.getItem("own-spa-token");

                    if (!token) {
                        return;
                    }
                    const tokenData = Jwt.decode(token);

                    if (tokenData.user.user.role === "admin") {
                        next();
                    } else {
                        next("/404");
                    }
                },
            },
        ],
        beforeEnter(to, from, next) {
            const token = localStorage.getItem("own-spa-token");
            if (token != null) {
                next();
            } else {
                next("/login");
            }
        },
    },
    { path: "*", name: "error404", component: Error404 },
];

const router = new VueRouter({
    mode: "history",
    routes,
});

function loadLocalMessages() {
    const locales = require.context(
        "../../src/locales/backend",
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
    locale: process.env.VUE_APP_I18N_LOCALE || "en", //Set locale
    fallbackLocale: process.env.VUE_APP_I18N_FALLBACK_LOCALE || "en", //Set fallback locale
    messages: loadLocalMessages(), //Set locale messages
});

const opts = {
    lang: {
        t: (key, ...params) => i18n.t(key, params),
    },
};

export default new Vuetify(opts);

// Directives
Vue.directive("tr-class-on-hover", {
    bind(el, binding) {
        // console.log("tr-class-on-hover binded");
        const { value = "" } = binding;
        el.addEventListener("mouseenter", () => {
            el.closest("tr").classList.add("actions-hover-" + value);
        });
        el.addEventListener("mouseleave", () => {
            el.closest("tr").classList.remove("actions-hover-" + value);
        });
    },
});

const app = new Vue({
    el: "#app",
    vuetify: new Vuetify(),
    i18n,
    router,
    store,
});
