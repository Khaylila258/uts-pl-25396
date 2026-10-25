import axios from 'axios'
import { auth } from '../stores/auth'
import router from '../router'

// Alamat API dibaca dari variabel lingkungan (.env), tidak ditulis mati di komponen.
const http = axios.create({
  baseURL: import.meta.env.VITE_API_URL || '/api',
  headers: { Accept: 'application/json' },
})

// Setiap permintaan otomatis membawa token yang tersimpan.
http.interceptors.request.use((config) => {
  if (auth.token) config.headers.Authorization = `Bearer ${auth.token}`
  return config
})

// Jika token ditolak server (401), hapus sesi dan kembali ke halaman login.
http.interceptors.response.use(
  (res) => res,
  (err) => {
    if (err.response?.status === 401 && auth.token) {
      auth.clear()
      router.push({ name: 'login' })
    }
    return Promise.reject(err)
  },
)

export default http
