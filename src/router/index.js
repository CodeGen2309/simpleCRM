import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/setup',
    name: 'setupPage',
    component: () => {return import('../pages/setupPage.vue') }
  },
  {
    path: '/flow',
    name: 'flowPage',
    component: () => {return import('../pages/flowPage.vue') }
  },
  {
    path: '/',
    name: 'nowPage',
    component: () => {return import('../pages/nowPage.vue') }
  },
  {
    path: '/month',
    name: 'perMonthPage',
    component: () => {return import('../pages/perMonthPage.vue') }
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
