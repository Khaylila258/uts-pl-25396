<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import http from '../api/http'
import { auth } from '../stores/auth'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')
const errors = ref({})
const loading = ref(false)

async function login() {
  loading.value = true
  error.value = ''
  errors.value = {}
  try {
    const { data } = await http.post('/login', { email: email.value, password: password.value })
    auth.set(data.token, data.user)          // simpan token
    router.push({ name: 'items' })
  } catch (e) {
    if (e.response?.status === 422) errors.value = e.response.data.errors
    else error.value = e.response?.data?.message || 'Tidak dapat terhubung ke server.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <form class="login" @submit.prevent="login">
    <h2>Masuk ke TitipIn</h2>
    <div>
      <input v-model="email" type="email" placeholder="Email" />
      <p v-if="errors.email" class="err">{{ errors.email[0] }}</p>
    </div>
    <div>
      <input v-model="password" type="password" placeholder="Password" />
      <p v-if="errors.password" class="err">{{ errors.password[0] }}</p>
    </div>
    <button class="btn" :disabled="loading">{{ loading ? 'Memproses...' : 'Login' }}</button>
    <p v-if="error" class="err">{{ error }}</p>
  </form>
</template>

<style scoped>
.login { max-width: 340px; margin: 70px auto; background: #fff; padding: 26px; border-radius: 12px; box-shadow: 0 2px 8px #0002; display: grid; gap: 12px; }
</style>
