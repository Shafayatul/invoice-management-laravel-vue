import api from "../../api";
import { snakeToCamel } from "../../helpers";
import { mutations } from "../helpers";

const initialState = () => ({
    pagination: {
        currentPage: 1,
        totalPage: 1,
        perPage: 15
    },
    expense: [],
    companyList: []
});

const state = initialState();

const getters = {
    $pagination: s => s.pagination,
    $expense: s => s.expense,
    $companyList: s => s.companyList
};

const actions = {
    fetchExpense: async ({ commit }) => {
        // let res = await api.company.getAll();
        // console.log(res);
        let { error, ...data } = await api.expense.getAll();
        if (error) return { error, ...data };
        commit("SET", {
            //   pagination: {
            //       totalPage: data.users.total,
            //       perPage: data.users.perPage,
            //       currentPage: data.users.currentPage
            //   },
            expense: data.expenses.data
        });
        return data;
    },
    deleteExpense: async ({ __, dispatch }, id) => {
        let res = await api.expense.delete(id);
        if (res.error) return res;
        //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchExpense");
        return res;
    },
    addExpense: async ({ __, dispatch }, data) => {
        let res = await api.expense.create(data);
        if (res.error) return res;
        //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchExpense");
        return res;
    },
    updateExpense: async ({ __, dispatch }, data) => {
    let res = await api.expense.update(data);
        if (res.error) return res;
        dispatch("fetchExpense");
        return res;
    },

    fetchCompanyList: async ({ commit }) => {
        let { error, ...data } = await api.company.compnayList();
        if (error) return { error, ...data };
        commit("SET", {
            //   pagination: {
            //       totalPage: data.users.total,
            //       perPage: data.users.perPage,
            //       currentPage: data.users.currentPage
            //   },
            companyList: data.companies
        });
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
