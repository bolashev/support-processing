import './bootstrap'
import '../sass/app.sass'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './components/App.vue'
import router from './router'
import { useToastsStore } from './stores/toasts'

const pinia = createPinia()
const app = createApp(App)
app.use(pinia)
app.use(router)

window.__toastsStore = useToastsStore()

app.mount('#app')
