import API from '../../api'
import { cookies, diff } from '../../helpers'
import { createMutations } from '../helpers'

const initalState = () => ({
  isAuth: false,
  accessToken: null,
  user: null,
})

const state = initalState()
const mutations = createMutations(['SET', 'RESET'])

const getters = {
    $user: ({ user }) => user,
    $isAuth: ({ isAuth }) => isAuth,
    $authRole :s => s.user && s.user.role
};

const actions = {
    login: async ({ commit }, payload) => {
        let res = await API.auth.login(payload);
        if (!res.error) {
            // const expires = payload.remember_me
            //   ? diff.day(res.expiresAt) : null;
            // const expires = 7
            cookies.set(
                { key: "isAuth", value: true, expires: 7 },
                { key: "authRole", value: res.role, expires: 7 },
                { key: "accessToken", value: res.token, expires: 7 }
                // { key: 'userId', value: res.user.id, expires },
            );
            commit("SET", {
                isAuth: true,
                accessToken: res.token,
                user:{role: res.role}
            });
        }
        return res;
    },
    logout: async ({ commit }) => {
        await API.auth.logout();
        commit("RESET", initalState());
        cookies.remove("isAuth", "accessToken", "userId");
        return { error: false };
    },
    fetchProfile: async ({ commit }) => {
        let res = await API.auth.fetchProfile();
        if (!res.error)
            commit("SET", {
                user: res.data
            });
        return res;
    },
    forgotPassword: async (__, data) => {
        let res = await API.auth.forgot(data);
        if (res.error) return res;
        return res;
    },
    resetPassword: async (__, data) => {
        let res = await API.auth.reset(data);
        if (res.error) return res;
        return res;
    },
    checkTokenValidity: async (__, token) => {
        let res = await API.auth.check({token});
        if (res.error) return res;
        return res;
    },
    updatePassword: async (__, data) => {
        let res = await API.auth.update(data);
        if (res.error) return res;
        return res;
    }
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
}