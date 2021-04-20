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
                    component: view("Company")
                },
                {
                    path: "users",
                    name: "Users",
                    component: view("Users")
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
                }
                // {
                //     path: "expense-details",
                //     name: "ExpenseDetails",
                //     component: view("ExpenseDetails")
                // }
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

router.beforeEach(async (to, _, next) => {
    let isAuth = store.getters["AUTH/$isAuth"];
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuth)
            return next({
                path: "/login",
                query: { redirect: to.fullPath }
            });
        return next();
    } else if (to.path === "/login" && isAuth) {
        return next({ path: "/" });
    }
    return next();
});

export default router;
