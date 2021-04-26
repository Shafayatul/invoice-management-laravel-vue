import api from "../../api";
import { snakeToCamel } from "../../helpers";
import { mutations } from "../helpers";

const initialState = () => ({
    pagination: {
        currentPage: 1,
        totalPage: 1,
        perPage: 15
    },
    income: [],
//     companyList: []
});

const state = initialState();

const getters = {
    $pagination: s => s.pagination,
    $income: s => s.income
    //     $companyList: s => s.companyList
};

const actions = {
    fetchIncome: async ({ commit },payload) => {
        // let res = await api.company.getAll();
        // console.log(res);
        let { error, ...data } = await api.income.getAll(payload);
        if (error) return { error, ...data };
        commit("SET", {
            pagination: {
                totalPage: data.incomes.total,
                perPage: data.incomes.perPage,
                currentPage: data.incomes.currentPage
            },

            income: data.incomes.data
        });
          console.log(data);
        return data;
    },
    deleteIncome: async ({ __, dispatch }, id) => {
        let res = await api.income.delete(id);
        if (res.error) return res;
        //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchIncome");
        return res;
    },
    addIncome: async ({ __, dispatch }, data) => {
        let res = await api.income.create(data);
        if (res.error) return res;
        //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchIncome");
        return res;
    },
    updateIncome: async ({ __, dispatch }, data) => {
        let res = await api.income.update(data);
        if (res.error) return res;
        dispatch("fetchIncome");
        return res;
    },

//     fetchCompanyList: async ({ commit }) => {
//         let { error, ...data } = await api.company.compnayList();
//         if (error) return { error, ...data };
//         commit("SET", {
//             //   pagination: {
//             //       totalPage: data.users.total,
//             //       perPage: data.users.perPage,
//             //       currentPage: data.users.currentPage
//             //   },
//             companyList: data.companies
//         });
//         return data;
//     }
};

const module = {
    state,
    getters,
    actions,
    mutations,
    namespaced: true
};

export default module;
