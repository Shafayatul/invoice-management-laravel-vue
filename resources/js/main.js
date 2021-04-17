import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import VueToast from 'vue-toast-notification';

import { cookies } from './helpers'
import { registerGlobalComponents } from './components'

// Stylesheets
import './assets/css/variables.css'
import './assets/scss/main.scss'
import 'vue-toast-notification/dist/theme-sugar.css';

// Proggresive web app
import './registerServiceWorker'

// Global components
registerGlobalComponents(Vue)

// Plugins
Vue.use(VueToast, { dismissible: true });

// Sync cookies
const {
  isAuth,
  userId,
  accessToken
} = cookies.get('isAuth', 'userId', 'accessToken')
store.commit('AUTH/SET', {
  isAuth:true,
  accessToken:'aweaweklajwekl',
  user: userId ? {
    id: userId
  } : 1
})

// Disabling production tips
Vue.config.productionTip = false

new Vue({
  router,
  store,
  vuetify,
  created() { this.syncTheme() },
  methods: {
    syncTheme() {
      const theme = this.$store.getters['$theme']
      this.$vuetify.theme.dark = theme.dark
    }
  },
  render: h => h(App)
}).$mount('#app')
