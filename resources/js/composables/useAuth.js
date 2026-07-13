import { ref } from 'vue'

const user = ref(window.__INITIAL_USER__ || null)

export function useAuth() {
    return { user }
}
