import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store";
import { view } from "../helpers";

// Components
import Home from "@/views/Home.vue";
import DefaultLayout from "@/layouts/Default.vue";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    base: "/",
    routes: [
        {
            path: "/",
            component: DefaultLayout,
            meta: { requiresAuth: true },
            children: [
                {
                    path: "/",
                    name: "Home",
                    component: Home
                },
                {
                    path: "company",
                    name: "Company",
                    component: view("Company"),
                    meta: {
                        requiresRole: "super admin"
                    }
                },
                {
                    path: "users",
                    name: "Users",
                    component: view("Users"),
                    meta: {
                        requiresRole: "super admin"
                    }
                },
                {
                    path: "income",
                    name: "Income",
                    component: view("Income")
                },
                {
                    path: "expense",
                    name: "Expense",
                    component: view("Expense")
                },
                {
                    path: "invoice-details",
                    name: "InvoiceDetails",
                    component: view("Invoice")
                },
                {
                    path: "payment-category",
                    name: "paymentCategory",
                    component: view("PaymmentCategory")
                },
                {
                    path: "client",
                    name: "Client",
                    component: view("Client"),
                      meta: {
                        requiresRole: "admin"
                    }
                },
                {
                    path: "invoice-history",
                    name: "invoicehistory",
                    component: view("InvoiceHistories")
                }
            ]
        },
        {
            path: "/login",
            name: "Login",
            component: view("Login")
        },
        {
            path: "/forgot-password",
            name: "ForgotPassword",
            component: view("ForgotPassword")
        },
        {
            path: "/update-password",
            name: "updatePassword",
            component: view("UpdatePassword")
        },
        {
            path: "/change-password/:token",
            name: "ChangePassword",
            component: view("ResetPassword")
        },
        {
            path: "*",
            name: "NotFound",
            component: view("NotFound")
        }
    ]
});
// let role='admin'
// router.beforeEach(async (to, _, next) => {
//     let isAuth = store.getters["AUTH/$isAuth"];
//     if (to.matched.some(record => record.meta.requiresAuth)) {
//         if (!isAuth)
//             return next({
//                 path: "/login",
//                 query: { redirect: to.fullPath }
//             });
//         return next();
//     } else if (to.path === "/login" && isAuth) {
//         return next({ path: "/" });
//     }
//     return next();
// });

router.beforeEach(async (to, from, next) => {
    const isAuth = store.getters["AUTH/$isAuth"];
    const authRole = store.getters["AUTH/$authRole"];
    if (to.matched.some(record => record.meta.requiresAuth)) {
        let requiresRole;
        const record = to.matched.find(record => record.meta.requiresRole);
        if (record) requiresRole = record.meta.requiresRole;
        if (!isAuth || !authRole) {
            return next({
                path: "/login",
                query: { redirect: to.fullPath }
            });
        }
        if (requiresRole && requiresRole !== authRole) {
            return next( "/not-found");
        }
        return next();
    } else if (
        isAuth &&
        authRole &&
        to.path.includes("login")
    ) {
        return next("/");
    }
    return next();
});

export default router;
