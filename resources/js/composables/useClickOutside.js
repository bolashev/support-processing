import { onMounted, onBeforeUnmount } from 'vue'

export function useClickOutside(elementRef, callback, options = {}) {
    const { ignore = null } = options

    function handler(e) {
        if (elementRef.value && elementRef.value.contains(e.target)) return
        if (ignore && typeof ignore === 'function' && ignore(e)) return
        callback()
    }

    onMounted(() => document.addEventListener('click', handler))
    onBeforeUnmount(() => document.removeEventListener('click', handler))
}
