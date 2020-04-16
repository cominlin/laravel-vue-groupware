import Vue from 'vue'
import VueRouter from 'vue-router'
import LoginPage from '../pages/login'
import IndexPage from '../pages/index'
import NotFoundPage from '../pages/404'

Vue.use(VueRouter)

let routes = [
  {
    path: '/login',
    name: 'login',
    component: LoginPage
  },
  {
    path: '/',
    name: 'index',
    component: IndexPage,
    meta: { middlewareAuth: true }
  },
  {
    path: '/*',
    name: '404',
    component: NotFoundPage
  }
]

const router = new VueRouter({
  mode: 'history',
  routes: routes,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.middlewareAuth)) {
    if (!window.auth.check()) {
      next({
        path: '/login',
        query: { redirect: to.fullPath }
      })
      return
    }
  }
  next()
})

export default router