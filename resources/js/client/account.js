import { api } from '@/bootstrap'

export const account = {
    get() {
        return api.get('/account')
    },
}
