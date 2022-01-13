import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import dbinter from './modules/dbInterface'

createApp(App)
  .use(router)
  .use(dbinter)
  .mount('#app')