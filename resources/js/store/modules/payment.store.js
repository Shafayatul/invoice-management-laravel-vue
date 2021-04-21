import api from "../../api";
import { snakeToCamel } from "../../helpers";
import { mutations } from "../helpers";

const initialState = () => ({
    pagination: {
        currentPage: 1,
        totalPage: 1,
        perPage: 15
    },
    payment: []
});

const state = initialState();

const getters = {
    $pagination: s => s.pagination,
    $payment: s => s.payment.sort((a, b) => b.createdAt - a.createdAt)
};

const actions = {
    fetchPayment: async ({ commit }) => {
        let { error, ...data } = await api.payment.getAll();
        if (error) return { error, ...data };
        commit("SET", {
            // pagination: {
            //   totalPage: data.users.total,
            //   perPage: data.users.perPage,
            //   currentPage: data.users.currentPage,
            // },
            payment: data.paymentCategories.data
        });
        console.log(data);
        return data;
    },
    deletePayment: async ({ commit, dispatch }, id) => {
        let res = await api.payment.delete(id);
        if (res.error) return res;
        dispatch("fetchPayment");
        return res;
    },
    addPayment: async ({ __, dispatch }, data) => {
        let res = await api.payment.create(data);
        if (res.error) return res;
        //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchPayment");
        return res;
    },
    updatePayment: async ({ __, dispatch }, data) => {
        let res = await api.payment.update(data, data.id);
        if (res.error) return res;
        dispatch("fetchPayment");
        return res;
    },

};

const module = {
    state,
    getters,
    actions,
    mutations,
    namespaced: true
};

export default module;
