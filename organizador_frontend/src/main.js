import './style.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import api from './services/api' //! axios

const app = createApp(App)

app.provide('api', api)

app.config.globalProperties.$api = api

app.use(router)
app.mount('#app')
