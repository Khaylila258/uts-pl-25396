import { reactive } from 'vue'

const KEY = 'titipin_token'

// State sederhana yang dipakai bersama; token disimpan di localStorage
// agar tetap ada setelah halaman dimuat ulang.
export const auth = reactive({
  token: localStorage.getItem(KEY),
  user: JSON.parse(localStorage.getItem('titipin_user') || 'null'),

  set(token, user) {
    this.token = token
    this.user = user
    localStorage.setItem(KEY, token)
    localStorage.setItem('titipin_user', JSON.stringify(user))
  },
  clear() {
    this.token = null
    this.user = null
    localStorage.removeItem(KEY)
    localStorage.removeItem('titipin_user')
  },
})
