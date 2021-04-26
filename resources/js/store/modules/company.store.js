import api from "../../api";
import { snakeToCamel } from "../../helpers";
import { mutations } from "../helpers";

const initialState = () => ({
    pagination: {
        currentPage: 1,
        totalPage: null,
        perPage: null
    },
    company: [],
    companyList: []
});

const state = initialState();

const getters = {
    $pagination: s => s.pagination,
    $company: s => s.company.sort((a, b) => b.createdAt - a.createdAt),
    $companyList: s => s.companyList
};

const actions = {
    fetchCompany: async ({ commit },payload) => {
        // let res = await api.company.getAll();
        // console.log(res);
        let { error, ...data } = await api.company.getAll(payload);
        if (error) return { error, ...data };
        commit("SET", {
            pagination: {
                totalPage: data.companies.total,
                perPage: +data.companies.perPage,
                currentPage: data.companies.currentPage
            },
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
