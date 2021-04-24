import api from "../../api";
import { snakeToCamel } from "../../helpers";
import { mutations } from "../helpers";

const initialState = () => ({
    pagination: {
        currentPage: 1,
        totalPage: 1,
        perPage: 15
    },
    client: [],
    clientList: []
});

const state = initialState();

const getters = {
    $pagination: s => s.pagination,
    $client: s => s.client.sort((a, b) => b.createdAt - a.createdAt),
    $clientList: s => s.clientList
};

const actions = {
    fetchClient: async ({ commit }) => {
        let { error, ...data } = await api.client.getAll();
        if (error) return { error, ...data };
        commit("SET", {
            // pagination: {
            //   totalPage: data.users.total,
            //   perPage: data.users.perPage,
            //   currentPage: data.users.currentPage,
            // },
            client: data.clients.data
        });
        return data;
    },
    deleteClient: async ({ commit, dispatch }, id) => {
        let res = await api.client.delete(id);
        if (res.error) return res;
        // commit("DELETE", { key: id, array: "users" });
        dispatch("fetchClient");
        return res;
        fetchClient;
    },
    addClient: async ({ __, dispatch }, data) => {
        let res = await api.client.create(data);
        if (res.error) return res;
        //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchClient");
        return res;
    },
    updateClient: async ({ __, dispatch }, data) => {
        let res = await api.client.update(data, data.client_id);
        if (res.error) return res;
        dispatch("fetchClient");
        return res;
    },
    fetchClientList: async ({ commit }) => {
        let { error, ...data } = await api.client.clientList();
        if (error) return { error, ...data };
        commit("SET", {
            //   pagination: {
            //       totalPage: data.users.total,
            //       perPage: data.users.perPage,
            //       currentPage: data.users.currentPage
            //   },
            clientList: data.clients
        });
        console.log(data,'fetchClinetList');
        return data;
    }
    //     blockUsers: async ({ __, dispatch }, data) => {
    //         let res = await api.users.block(data, data.user_id);
    //         if (res.error) return res;
    //         dispatch("fetchUsers");
    //         return res;
    //     },
    //     reAssignCompany: async ({ __, dispatch }, data) => {
    //         let res = await api.users.reAssign(data);
    //         if (res.error) return res;
    //         dispatch("fetchUsers");
    //         return res;
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
