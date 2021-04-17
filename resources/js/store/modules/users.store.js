import api from '../../api'
import { snakeToCamel } from '../../helpers'
import { mutations } from '../helpers'

const initialState = () => ({
  pagination: {
    currentPage: 1,
    totalPage: 1,
    perPage: 15,
  },
  users: []
})

const state = initialState()

const getters = {
  $pagination: s => s.pagination,
  $users: s => s.users.sort((a, b) => b.createdAt - a.createdAt)
}

const actions = {
  fetchUsers: async ({ commit }) => {
    let { error, ...data } = await api.users.getAll()
    if (error) return { error, ...data }
    commit('SET', {
      pagination: {
        totalPage: data.users.total,
        perPage: data.users.perPage,
        currentPage: data.users.currentPage,
      },
      users: data.users.data
    })
    return data
  },
  deleteUser: async ({ commit, dispatch }, id) => {
    let res = await api.users.delete(id)
    if (res.error) return res
    commit('DELETE', { key: id, array: 'users' })
    dispatch('fetchUsers')
    return res
  }
}

const module = {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
}

export default module