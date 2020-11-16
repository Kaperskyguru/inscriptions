require("./bootstrap");
import Vue from "vue";
import VueRouter from "vue-router";
import VeeValidate from "vee-validate";
import VeeValidateLaravel from "vee-validate-laravel";
import vmodal from "vue-js-modal";
import Cleave from "vue-cleave-component";
import DefaultLayout from "./views/layouts/Default";
import DashboardLayout from "./views/layouts/Dashboard";
import AdminLayout from "./views/layouts/Admin";
import excel from "vue-excel-export";
import VueSimpleAccordion from "vue-simple-accordion";
// import "vue-simple-accordion/dist/vue-simple-accordion.css";
Vue.component("pagination", require("laravel-vue-pagination"));

import App from "./views/App";

import router from "./router";
import { store } from "./store";

import { i18n } from "./plugins/i18n.js";

import axios from "axios";
import {
    onSuccess,
    onError,
    beforeRequestSuccess,
    beforeRequestError
} from "./interceptors/Jwt";

axios.interceptors.request.use(beforeRequestSuccess, beforeRequestError);
axios.interceptors.response.use(onSuccess, onError);

// localStorage.setItem("default_year", new Date().getFullYear() + 1);
Vue.use(VueSimpleAccordion, {
    tags: {
        list: "dl",
        list__item: "div",
        item__heading: "dt",
        heading__content: "span",
        heading__icon: "span",
        item__content: "div"
    }
});
Vue.use(VeeValidate, {
    locale: window.locale,
    fieldsBagName: "vvFields"
});

Vue.use(VeeValidateLaravel);
Vue.use(VueRouter);
Vue.use(vmodal);
Vue.use(Cleave);
Vue.use(excel);
// Vue.use(Vuex);

Vue.component("default-layout", DefaultLayout);
Vue.component("admin-layout", AdminLayout);
Vue.component("dashboard-layout", DashboardLayout);
Vue.prototype.$appTheme = window.appConfigs.theme;

const app = new Vue({
    el: "#app",
    components: { App },
    i18n: i18n,
    router,
    watch: {
        $route(to, from) {
            // Code
            store.dispatch("feedback/clearFeedback");
        }
    },
    store
});
