import { defineStore } from 'pinia'
import { computed, ref } from 'vue'

type Timer = ReturnType<typeof setTimeout>
type Interval = ReturnType<typeof setInterval>

export const useTopLoaderStore = defineStore('topLoader', () => {
  const pending = ref(0)

  // UI state
  const visible = ref(false)
  const progress = ref(0) // 0..100

  const active = computed(() => visible.value)

  // internals
  let showTimer: Timer | null = null
  let tickTimer: Interval | null = null
  let finishing = false

  const SHOW_DELAY_MS = 120 // avoid flicker for ultra-fast requests
  const FINISH_MS = 220 // 100% animation duration
  const HIDE_AFTER_MS = 120 // small delay to let users see completion

  function clearTimers() {
    if (showTimer) clearTimeout(showTimer)
    if (tickTimer) clearInterval(tickTimer)
    showTimer = null
    tickTimer = null
  }

  function startTicking() {
    if (tickTimer) return

    tickTimer = setInterval(() => {
      if (!visible.value || finishing) return

      // “real feel”: move fast early, then slow down as it approaches 90–95
      const p = progress.value

      if (p < 60)
        progress.value = Math.min(60, p + 6) // fast
      else if (p < 80)
        progress.value = Math.min(80, p + 2.5) // medium
      else if (p < 92)
        progress.value = Math.min(92, p + 1.2) // slow
      else if (p < 96) progress.value = Math.min(96, p + 0.4) // very slow
      // never reach 100% until stop()
    }, 120)
  }

  function start() {
    pending.value++

    // if already visible/ticking, just keep it going
    if (visible.value) return

    // delay showing to avoid flicker
    if (!showTimer) {
      showTimer = setTimeout(() => {
        // only show if still pending
        if (pending.value > 0) {
          visible.value = true
          // on first show, start from a small value so it feels “started”
          progress.value = Math.max(progress.value, 8)
          startTicking()
        }
      }, SHOW_DELAY_MS)
    }
  }

  function stop() {
    pending.value = Math.max(0, pending.value - 1)

    // still pending requests -> do nothing
    if (pending.value > 0) return

    // no pending: finish
    clearTimers()

    // if it never became visible (fast request), just reset and exit
    if (!visible.value) {
      progress.value = 0
      finishing = false
      return
    }

    finishing = true
    progress.value = Math.max(progress.value, 96)

    // animate to 100, then hide
    const startAt = progress.value
    const startTime = performance.now()

    const raf = () => {
      const t = (performance.now() - startTime) / FINISH_MS
      const clamped = Math.min(1, t)

      progress.value = startAt + (100 - startAt) * clamped

      if (clamped < 1) requestAnimationFrame(raf)
      else {
        setTimeout(() => {
          visible.value = false
          progress.value = 0
          finishing = false
        }, HIDE_AFTER_MS)
      }
    }

    requestAnimationFrame(raf)
  }

  async function wrap<T>(fn: () => Promise<T>): Promise<T> {
    start()

    try {
      return await fn()
    } finally {
      stop()
    }
  }

  return { active, progress, start, stop, wrap }
})
