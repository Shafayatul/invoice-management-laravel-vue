import api from "../../api";
import { snakeToCamel } from "../../helpers";
import { mutations } from "../helpers";

const initialState = () => ({
    pagination: {
        currentPage: 1,
        totalPage: 1,
        perPage: 15
    },
    company: []
});

const state = initialState();

const getters = {
    $pagination: s => s.pagination,
    $company: s => s.company.sort((a, b) => b.createdAt - a.createdAt)
};

const actions = {
    fetchCompany: async ({ commit }) => {
          // let res = await api.company.getAll();
          // console.log(res);
        let { error, ...data } = await api.company.getAll();
        if (error) return { error, ...data };
        commit("SET", {
            //   pagination: {
            //       totalPage: data.users.total,
            //       perPage: data.users.perPage,
            //       currentPage: data.users.currentPage
            //   },
            company: data.companies.data
        });
        return data;
    },
    deleteCompany: async ({ __, dispatch }, id) => {
        let res = await api.company.delete(id);
        if (res.error) return res;
     //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchCompany");
        return res;
    },
    addCompany: async ({ __, dispatch }, data) => {
        let res = await api.company.create(data);
        if (res.error) return res;
     //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchCompany");
        return res;
    },
    updateCompany: async ({ __, dispatch }, data) => {
        let res = await api.company.update(data, data.company_id);
        if (res.error) return res;
        dispatch("fetchCompany");
        return res;
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
