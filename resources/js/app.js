import './bootstrap'
import '../sass/app.sass'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './components/App.vue'
import router from './router'
import { useToastsStore } from './stores/toasts'
import { useAccountStore } from './stores/account'

const pinia = createPinia()
const app = createApp(App)
app.use(pinia)
app.use(router)

window.__toastsStore = useToastsStore()

const accountStore = useAccountStore()
accountStore.fetchAccount().then(() => {
    app.mount('#app')
})
