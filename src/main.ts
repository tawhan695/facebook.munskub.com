import './assets/main.css'
import 'vue3-toastify/dist/index.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import Vue3Toastify, { type ToastContainerOptions } from 'vue3-toastify'
import App from './App.vue'
import router from './router'

const app = createApp(App)

// app.use(cors({ origin: ['http://localhost:8082'] }))
app.use(createPinia())
app.use(router)
app.use(Vue3Toastify, {
  autoClose: 5000,
  theme: 'colored',
  limit: 3,
  dangerouslyHTMLString: true
} as ToastContainerOptions)

app.mount('#app')
