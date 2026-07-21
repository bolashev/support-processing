import { computed, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

function isEmpty(v) {
    if (v === null || v === undefined) return true
    if (typeof v === 'string') return v === ''
    if (Array.isArray(v)) return v.length === 0
    return false
}

function equals(a, b) {
    if (Array.isArray(a) && Array.isArray(b)) {
        return a.length === b.length && a.every((v, i) => v === b[i])
    }
    return a === b
}

export function useRouteQuery(key, defaultValue, options = {}) {
    const {
        transform = (v) => v,
        parse = (v) => v,
        debounce = 0,
        clearOnDefault = false,
        clearKeys = [],
    } = options

    const route = useRoute()
    const router = useRouter()

    const fromQuery = (query) => {
        const v = query[key]
        if (v === undefined || v === null) return defaultValue
        return parse(v)
    }

    const local = ref(fromQuery(route.query))
    let timer = null

    const value = computed({
        get() {
            return local.value
        },
        set(v) {
            local.value = v
            clearTimeout(timer)
            const apply = () => {
                const query = { ...route.query }
                if (clearOnDefault && equals(v, defaultValue)) {
                    delete query[key]
                } else if (isEmpty(v)) {
                    delete query[key]
                } else {
                    query[key] = transform(v)
                }
                for (const k of clearKeys) {
                    delete query[k]
                }
                router.replace({ query })
            }
            if (debounce > 0) {
                timer = setTimeout(apply, debounce)
            } else {
                apply()
            }
        },
    })

    watch(() => route.query[key], (v) => {
        const next = v === undefined || v === null ? defaultValue : parse(v)
        if (!equals(next, local.value)) {
            local.value = next
        }
    })

    return value
}
