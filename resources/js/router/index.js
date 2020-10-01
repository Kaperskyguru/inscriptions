import Vue from "vue";
import Router from "vue-router";

import { i18n } from "../plugins/i18n.js";

import UsersIndex from "../views/UsersIndex";
import UsersCreate from "../views/UsersCreate";
import UsersEdit from "../views/UsersEdit";
import UsersLogin from "../views/UsersLogin";
import ForgotPassword from "../views/ForgotPassword";
import ResetPasswordForm from "../views/ResetPasswordForm";

import RoutinesIndex from "../views/RoutinesIndex";
import RoutinesCreate from "../views/RoutinesCreate";
import RoutinesEdit from "../views/RoutinesEdit";

import NotFound from "../views/NotFound";

import PagesStyleGuide from "../views/StyleGuide";

import DashboardsIndex from "../views/DashboardIndex";

import AdminIndex from "../views/AdminIndex";
import AdminEventIndex from "../views/AdminEventIndex";
import AdminSubscriptionShow from "../views/AdminSubscriptionShow";
import AdminScheduleShow from "../views/AdminScheduleShow";
import AdminScheduleOrderPosition from "../views/AdminScheduleOrderPosition";
import AdminScheduleHour from "../views/AdminScheduleHour";
import AdminRoutinesCreate from "../views/AdminRoutinesCreate";
import AdminRoutinesEdit from "../views/AdminRoutinesEdit";

import DancersIndex from "../views/DancersIndex";

import organizationsEdit from "../views/OrganizationsEdit";

import SubscriptionsShow from "../views/SubscriptionsShow";

import MusicShow from "../views/MusicShow";

import AppInit from "./guards/AppInit";
import RedirectIfLoggedIn from "./guards/RedirectIfLoggedIn";
import IsLoggedIn from "./guards/IsLoggedIn";

Vue.use(Router);

const router = new Router({
    mode: "history",
    base: window.locale,
    routes: [
        {
            path: "/",
            name: "users.login",
            component: UsersLogin,
            beforeEnter: RedirectIfLoggedIn
        },
        {
            path: i18n.t("routes.users.create"),
            name: "users.create",
            component: UsersCreate,
            beforeEnter: RedirectIfLoggedIn
        },
        {
            path: i18n.t("routes.users.forgotPassword"),
            name: "users.forgot",
            component: ForgotPassword
        },
        {
            path: i18n.t("routes.users.resetPasswordForm"),
            name: "users.resetPasswordForm",
            component: ResetPasswordForm
        },
        {
            path: i18n.t("routes.dashboard.index"),
            name: "dashboard.index",
            component: DashboardsIndex,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "dashboard"
            }
        },
        {
            path: i18n.t("routes.dashboard.organization"),
            name: "organization.edit",
            component: organizationsEdit,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "dashboard"
            }
        },
        {
            path: i18n.t("routes.dashboard.dancer"),
            name: "dashboard.dancer",
            component: DancersIndex,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "dashboard"
            }
        },
        {
            path: i18n.t("routes.dashboard.userEdit"),
            name: "dashboard.userEdit",
            component: UsersEdit,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "dashboard"
            }
        },
        {
            path: i18n.t("routes.dashboard.subscriptionsShow"),
            name: "dashboard.subscriptionsShow",
            component: SubscriptionsShow,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "dashboard"
            }
        },
        {
            path: i18n.t("routes.dashboard.music"),
            name: "dashboard.musicShow",
            component: MusicShow,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "dashboard"
            }
        },
        {
            path: i18n.t("routes.dashboard.routineCreate"),
            name: "routines.create",
            component: RoutinesCreate,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "dashboard"
            }
        },
        {
            path: i18n.t("routes.dashboard.routineEdit"),
            name: "routines.edit",
            component: RoutinesEdit,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "dashboard"
            }
        },
        {
            path: i18n.t("routes.dashboard.routineDuplicate"),
            name: "routines.duplicate",
            component: RoutinesEdit,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "dashboard"
            }
        },
        {
            path: i18n.t("routes.admin.index"),
            name: "admin.index",
            component: AdminIndex,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "admin",
                role: "admin"
            }
        },
        {
            path: i18n.t("routes.admin.event"),
            name: "admin.event",
            component: AdminEventIndex,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "admin",
                role: "admin"
            }
        },
        {
            path: i18n.t("routes.admin.subscription"),
            name: "admin.subscription",
            component: AdminSubscriptionShow,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "admin",
                role: "admin"
            }
        },
        {
            path: i18n.t("routes.admin.schedule"),
            name: "admin.schedule",
            component: AdminScheduleShow,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "admin",
                role: "admin"
            }
        },
        {
            path: i18n.t("routes.admin.scheduleOrderPosition"),
            name: "admin.scheduleOrderPosition",
            component: AdminScheduleOrderPosition,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "admin",
                role: "admin"
            }
        },
        {
            path: i18n.t("routes.admin.scheduleHour"),
            name: "admin.scheduleHour",
            component: AdminScheduleHour,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "admin",
                role: "admin"
            }
        },
        {
            path: i18n.t("routes.admin.routineCreate"),
            name: "admin.routineCreate",
            component: AdminRoutinesCreate,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "admin",
                role: "admin"
            }
        },
        {
            path: i18n.t("routes.admin.routineEdit"),
            name: "admin.routineEdit",
            component: AdminRoutinesEdit,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "admin",
                role: "admin"
            }
        },
        {
            path: i18n.t("routes.admin.routineDuplicate"),
            name: "admin.routineDuplicate",
            component: AdminRoutinesEdit,
            beforeEnter: IsLoggedIn,
            meta: {
                layout: "admin",
                role: "admin"
            }
        },
        {
            path: "/404",
            name: "404",
            component: NotFound
        },
        {
            path: "*",
            redirect: "/404"
        }
    ]
});

router.beforeEach(AppInit);

export default router;