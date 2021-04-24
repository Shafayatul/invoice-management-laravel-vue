import api from "../../api";
import { snakeToCamel } from "../../helpers";
import { mutations } from "../helpers";

const initialState = () => ({
    pagination: {
        currentPage: 1,
        totalPage: 1,
        perPage: 15
    },
    invoice: [],
    invoiceHistories: []
});

const state = initialState();

const getters = {
    $pagination: s => s.pagination,
    $invoice: s => s.invoice,
    $invoiceHistories: s => s.invoiceHistories
};

const actions = {
    fetchInvoice: async ({ commit }) => {
        // let res = await api.invoice.getAll();
        // console.log(res);
        let { error, ...data } = await api.invoice.getAll();
        console.log(data, "console from store");
        if (error) return { error, ...data };
        commit("SET", {
            //   pagination: {
            //       totalPage: data.users.total,
            //       perPage: data.users.perPage,
            //       currentPage: data.users.currentPage
            //   },
            invoice: data.invoices.data
        });
        return data;
    },
    deleteInvoice: async ({ __, dispatch }, id) => {
        let res = await api.invoice.delete(id);
        if (res.error) return res;
        //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchInvoice");
        return res;
    },
    addInvoice: async ({ __, dispatch }, data) => {
        let res = await api.invoice.create(data);
        if (res.error) return res;
        //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchInvoice");
        return res;
    },
    updateInvoice: async ({ __, dispatch }, data) => {
        let res = await api.invoice.update(data, data.invoice_id);
        if (res.error) return res;
        dispatch("fetchInvoice");
        return res;
    },
    fetchInvoiceHistories: async ({ commit }) => {
        // let res = await api.invoice.getAll();
        // console.log(res);
        let { error, ...data } = await api.invoice.getHistories();
        console.log(data, "console from store");
        if (error) return { error, ...data };
        commit("SET", {
            //   pagination: {
            //       totalPage: data.users.total,
            //       perPage: data.users.perPage,
            //       currentPage: data.users.currentPage
            //   },
            invoiceHistories: data.invoiceHostories.data
        });
        console.log(data, "fetchInvoiceHistories");
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
