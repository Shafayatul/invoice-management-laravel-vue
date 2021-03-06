import api from '../../api'
import { snakeToCamel } from '../../helpers'
import { mutations } from '../helpers'

const initialState = () => ({
  pagination: {
    currentPage: 1,
    totalPage: 1,
    perPage: 10,
  },
  users: []
})

const state = initialState()

const getters = {
  $pagination: s => s.pagination,
  $users: s => s.users.sort((a, b) => b.createdAt - a.createdAt)
}

const actions = {
    fetchUsers: async ({ commit }, payload) => {
        let { error, ...data } = await api.users.getAll(payload);
        if (error) return { error, ...data };
        commit("SET", {
            pagination: {
              totalPage: data.users.total,
              perPage: +data.users.perPage,
              currentPage: data.users.currentPage,
            },
            users: data.users.data
        });
        return data;
    },
    deleteUser: async ({ commit, dispatch }, id) => {
        let res = await api.users.delete(id);
        if (res.error) return res;
        commit("DELETE", { key: id, array: "users" });
        dispatch("fetchUsers");
        return res;
    },
    addUser: async ({ __, dispatch }, data) => {
        let res = await api.users.create(data);
        if (res.error) return res;
        //    commit("DELETE", { key: id, array: "users" });
        dispatch("fetchUsers");
        return res;
    },
    updateUsers: async ({ __, dispatch }, data) => {
        let res = await api.users.update(data, data.user_id);
        if (res.error) return res;
        dispatch("fetchUsers");
        return res;
    },
    blockUsers: async ({ __, dispatch }, data) => {
        let res = await api.users.block(data, data.user_id);
        if (res.error) return res;
        dispatch("fetchUsers");
        return res;
    },
    reAssignCompany: async ({ __, dispatch }, data) => {
        let res = await api.users.reAssign(data);
        if (res.error) return res;
        dispatch("fetchUsers");
        return res;
    }
};

const module = {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
}

export default module