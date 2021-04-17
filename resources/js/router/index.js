import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'
import { view } from '../helpers'

// Components
import Home from '@/views/Home.vue'
import DefaultLayout from '@/layouts/Default.vue'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: '/',
  routes: [
    {
      path: '/',
      component: DefaultLayout,
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          name: 'Home',
          component: Home
        },
        {
          path: 'users',
          name: 'Users',
          component: view('Users')
        },

      ]
    },
    {
      path: '/login',
      name: 'Login',
      component: view('Login')
    }
  ]
})

// router.beforeEach(async (to, _, next) => {
//   let isAuth = store.getters['AUTH/$isAuth'];
//   if (to.matched.some(record => record.meta.requiresAuth)) {
//     if (!isAuth) return next({
//       path: '/login', query: { redirect: to.fullPath }
//     })
//     return next()
//   }
//   else if (to.path === '/login' && isAuth) {
//     return next({ path: '/' })
//   }
//   return next()
// })

export default router
