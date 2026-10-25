<script setup>
import { useRouter } from 'vue-router'
import http from './api/http'
import { auth } from './stores/auth'

const router = useRouter()

async function logout() {
  try { await http.post('/logout') } catch (e) { /* token mungkin sudah kedaluwarsa */ }
  auth.clear()
  router.push({ name: 'login' })
}
</script>

<template>
  <header class="topbar">
    <span class="logo">TitipIn</span>
    <div v-if="auth.token" class="right">
      <span class="who">{{ auth.user?.name }}</span>
      <button class="btn out" @click="logout">Logout</button>
    </div>
  </header>
  <router-view />
</template>

<style>
:root { --g: #03ac0e; --bg: #f3f4f6; --tx: #1f2937; --mu: #6b7280; }
* { box-sizing: border-box; margin: 0; font-family: system-ui, Arial, sans-serif; }
body { background: var(--bg); color: var(--tx); }
.topbar { background: #fff; box-shadow: 0 1px 4px #0002; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 5; }
.logo { font-weight: 800; font-size: 22px; color: var(--g); }
.right { display: flex; gap: 12px; align-items: center; }
.who { color: var(--mu); font-size: 14px; }
.btn { background: var(--g); color: #fff; border: 0; padding: 9px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; }
.btn.out { background: #fff; color: var(--g); border: 1px solid var(--g); }
.btn.danger { background: #dc2626; }
.btn.sm { padding: 5px 10px; font-size: 12px; }
input, select, textarea { padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; width: 100%; }
.err { color: #dc2626; font-size: 12px; }
</style>
