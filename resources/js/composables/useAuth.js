import { ref } from 'vue'

export function useAuth() {
    const user = ref(window.__INITIAL_USER__ || null)

    return { user }
}
