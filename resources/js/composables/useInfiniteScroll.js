import { onBeforeUnmount, onMounted, watch } from 'vue'

export function useInfiniteScroll(targetRef, sentinelRef, onLoadMore, options = {}) {
    const { rootMargin = '100px', threshold = 0 } = options

    let observer = null
    let ticking = false

    function handleIntersect(entries) {
        if (ticking) return
        const entry = entries[0]
        if (entry && entry.isIntersecting) {
            ticking = true
            onLoadMore()
            requestAnimationFrame(() => {
                ticking = false
            })
        }
    }

    function start() {
        const root = targetRef.value
        const sentinel = sentinelRef.value
        if (!root || !sentinel || observer) return
        observer = new IntersectionObserver(handleIntersect, {
            root,
            rootMargin,
            threshold,
        })
        observer.observe(sentinel)
    }

    function stop() {
        if (observer) {
            observer.disconnect()
            observer = null
        }
        ticking = false
    }

    onMounted(start)

    watch([targetRef, sentinelRef], ([root, sentinel]) => {
        stop()
        if (root && sentinel) {
            start()
        }
    })

    onBeforeUnmount(stop)
}
