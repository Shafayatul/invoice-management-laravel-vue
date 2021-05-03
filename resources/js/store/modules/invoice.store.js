import api from "../../api";
import { snakeToCamel } from "../../helpers";
import { mutations } from "../helpers";

const initialState = () => ({
    pagination: {
        currentPage: 1,
        totalPage: 1,
        perPage: 15
    },
    paginationHistories:{
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
    $invoiceHistories: s => s.invoiceHistories,
    $paginationHistories: s => s.paginationHistories
};

const actions = {
    fetchInvoice: async ({ commit }, payload) => {
        // let res = await api.invoice.getAll();
        // console.log(res);
        let { error, ...data } = await api.invoice.getAll(payload);
        console.log(data, "console from store");
        if (error) return { error, ...data };
        commit("SET", {
            pagination: {
                totalPage: data.invoices.total,
                perPage: data.invoices.perPage,
                currentPage: data.invoices.currentPage
            },
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
    fetchInvoiceHistories: async ({ commit }, payload) => {
        // let res = await api.invoice.getAll();
        // console.log(res);
        let { error, ...data } = await api.invoice.getHistories(payload);
        console.log(data, "console from store");
        if (error) return { error, ...data };
        commit("SET", {
            paginationHistories: {
                totalPage: data.invoiceHostories.total,
                perPage: data.invoiceHostories.perPage,
                currentPage: data.invoiceHostories.currentPage
            },
            invoiceHistories: data.invoiceHostories.data
        });
        console.log(data, "fetchInvoiceHistories");
        return data;
    },
    paidInvoice: async ({ __, dispatch }, data) => {
        let res = await api.invoice.paidInvoice(data);
        if (res.error) return res;
        dispatch("fetchInvoice");
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
