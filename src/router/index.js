import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/setup',
    name: 'setup',
    component: () => {return import('../pages/setupPage.vue') }
  },
  {
    path: '/',
    name: 'nowPage',
    component: () => {return import('../pages/nowPage.vue') }
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
