import axios from 'axios'

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
})

api.interceptors.response.use(
    (response) => response,
    (error) => {
        const message = error.response?.data?.message
            || error.message
            || 'Произошла ошибка'

        if (typeof window !== 'undefined' && window.__toastsStore) {
            window.__toastsStore.error(message)
        }

        return Promise.reject(error)
    },
)

export { api }
