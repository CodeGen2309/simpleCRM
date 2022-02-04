import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import dbinter from './modules/dbInterface'
import formatter from './modules/formatNumber'

createApp(App)
  .use(router)
  .use(dbinter)
  .use(formatter)
  .mount('#app')