import Vue from 'vue'
import Vuex from 'vuex'
import syncLocalStorage from 'vuex-persistedstate'
import createLogger from 'vuex/dist/logger'
import { _isDev } from '../consts'

import {
  state,
  getters,
  mutations,
  actions
} from './global'
import modules from './modules'

let plugins = [syncLocalStorage({
  key: 'vvs-states',
  paths: [
    'drawer',
    'loading',
    'theme'
  ]
})]

if (_isDev) plugins.push(createLogger())

Vue.use(Vuex)

const store = new Vuex.Store({
  state,
  getters,
  mutations,
  actions,
  modules,
  plugins
})

export default store