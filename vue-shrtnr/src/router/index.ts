import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router'
import Home from '@/views/Home.vue'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import Dashboard from '@/views/Dashboard.vue'
import store from '@/store'

const routes: Array<RouteRecordRaw> = [
    {
      path: '/',
      name: 'Home',
      component: Home,
      beforeEnter: (to, from, next) => {
        if(store.getters['auth/authenticated']){
          return next({
            name: 'dashboard'
          })
        }
        next()
      }
    },
    {
      path: '/login', 
      name: 'Login',
      component: Login
    },
    {
      path: '/register', 
      name: 'Register',
      component: Register
    },
    {
      path: '/dashboard', 
      name: 'Dashboard',
      component: Dashboard,
      beforeEnter: (to, from, next) => {
        if(!store.getters['auth/authenticated']){
          return next({
            name: 'login'
          })
        }
        next()
      }
    },
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router