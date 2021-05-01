import api from "../../api";
import { snakeToCamel } from "../../helpers";
// import moment from "moment;";
import { mutations } from "../helpers";


const initialState = () => ({
    dashboard: {},
    lastMonthPaidInvoiceValue: [],
    lastMonthUnPaidInvoiceValue: [],
    lastMonthIncome: [],
    lastMonthExpense: [],
    lastMonthDate: []
});

const state = initialState();

const getters = {
    $dashboard: s => s.dashboard,
    $lastMonthPaidInvoiceValue: s => s.lastMonthPaidInvoiceValue,
    $lastMonthUnPaidInvoiceValue: s => s.lastMonthUnPaidInvoiceValue,
    $lastMonthIncome: s => s.lastMonthIncome,
    $lastMonthExpense: s => s.lastMonthExpense,
    $lastMonthDate: s => s.lastMonthDate.reverse()
};

const actions = {
    fetchDashboardData: async ({ commit }) => {
        let { error, ...data } = await api.dashboard.getAll();
        if (error) return { error, ...data };
        commit("SET", {
            //   pagination: {
            //       totalPage: data.users.total,
            //       perPage: data.users.perPage,
            //       currentPage: data.users.currentPage
            //   },
            dashboard: data,
            lastMonthPaidInvoiceValue: Object.values(
                data.lastMonthPaidInvoiceArr
            ),
            lastMonthUnPaidInvoiceValue: Object.values(
                data.lastMonthUnpaidInvoiceArr
            ),
            lastMonthIncome: Object.values(data.lastMonthIncomesArr),
            lastMonthExpense: Object.values(data.lastMonthExpensesArr),
            lastMonthDate: Object.keys(data.lastMonthPaidInvoiceArr)
        });
        console.log(data);
        return data;
    }
};

const module = {
    state,
    getters,
    actions,
    mutations,
    namespaced: true
};

export default module;
