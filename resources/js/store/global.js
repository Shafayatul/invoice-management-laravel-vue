import { createMutations } from "./helpers"

const initialState = () => ({
  drawer: true,
  loading: false,
  theme: {
    dark: false
  }
})

export const state = initialState()
export const mutations = createMutations(['SET'])

export const getters = {
  $theme: ({ theme }) => theme,
  $drawer: ({ drawer }) => drawer,
  $loading: ({ loading }) => loading,
}

export const actions = {
  toggleDrawer({ commit, state: { drawer } }, payload) {
    commit('SET', {
      drawer: payload === undefined
        ? !drawer
        : payload
    })
  },
  toggleLoading({ commit, state: { loading } }, payload) {
    commit('SET', {
      loading: payload === undefined
        ? !loading
        : payload
    })
  },
  toggleTheme({ commit, state: { theme: { dark } } }, payload) {
    commit('SET', {
      theme: {
        dark: payload === undefined
          ? !dark
          : payload
      }
    })
  },
}