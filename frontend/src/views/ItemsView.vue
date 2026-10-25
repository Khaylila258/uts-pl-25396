<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import http from '../api/http'
import ItemForm from '../components/ItemForm.vue'

const items = ref([])
const meta = reactive({ current_page: 1, last_page: 1, total: 0 })
const filter = reactive({ search: '', category: '', status: '' })
const page = ref(1)
const loading = ref(false)
const message = ref('')

const showForm = ref(false)
const editing = ref(null)
const formErrors = ref({})
const saving = ref(false)

const rupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n)

// READ: tampilkan data
async function load() {
  loading.value = true
  try {
    const { data } = await http.get('/items', { params: { ...filter, page: page.value, per_page: 12 } })
    items.value = data.data
    Object.assign(meta, data.meta)
  } finally {
    loading.value = false
  }
}

function openAdd() { editing.value = null; formErrors.value = {}; showForm.value = true }
function openEdit(it) { editing.value = it; formErrors.value = {}; showForm.value = true }

// CREATE dan UPDATE
async function save(body) {
  saving.value = true
  formErrors.value = {}
  try {
    if (editing.value) {
      await http.put(`/items/${editing.value.id}`, body)
      message.value = 'Barang berhasil diubah.'
    } else {
      await http.post('/items', body)
      message.value = 'Barang berhasil ditambahkan.'
    }
    showForm.value = false
    await load()
  } catch (e) {
    if (e.response?.status === 422) formErrors.value = e.response.data.errors
    else message.value = 'Gagal menyimpan data.'
  } finally {
    saving.value = false
  }
}

// DELETE
async function remove(it) {
  if (!confirm(`Hapus "${it.name}"?`)) return
  await http.delete(`/items/${it.id}`)
  message.value = 'Barang berhasil dihapus.'
  if (items.value.length === 1 && page.value > 1) page.value--
  await load()
}

let timer
watch(() => ({ ...filter }), () => { clearTimeout(timer); timer = setTimeout(() => { page.value = 1; load() }, 300) })
onMounted(load)
</script>

<template>
  <main>
    <div class="toolbar">
      <input v-model="filter.search" placeholder="Cari barang titipan..." class="grow" />
      <select v-model="filter.category">
        <option value="">Semua kategori</option>
        <option>Makanan</option><option>Fashion</option><option>Kecantikan</option><option>Aksesoris</option>
      </select>
      <select v-model="filter.status">
        <option value="">Semua status</option><option value="available">Available</option><option value="sold">Sold</option>
      </select>
      <button class="btn" @click="openAdd">+ Tambah Barang</button>
    </div>

    <p class="info">{{ loading ? 'Memuat...' : meta.total + ' barang' }} <span v-if="message" class="ok"> - {{ message }}</span></p>

    <div class="grid">
      <div v-for="it in items" :key="it.id" class="card">
        <img :src="it.image_url" :alt="it.name" loading="lazy" />
        <div class="body">
          <div class="name">{{ it.name }}</div>
          <div class="price">{{ rupiah(it.price) }}</div>
          <div class="meta">Titipan dari {{ it.origin_city }} | Stok: {{ it.stock }}</div>
          <div><span class="tag">{{ it.category }}</span> <span class="tag" :class="it.status">{{ it.status }}</span></div>
          <div class="actions">
            <button class="btn out sm" @click="openEdit(it)">Ubah</button>
            <button class="btn danger sm" @click="remove(it)">Hapus</button>
          </div>
        </div>
      </div>
    </div>

    <div class="pager" v-if="meta.last_page > 1">
      <button class="btn out sm" :disabled="page <= 1" @click="page--; load()">Sebelumnya</button>
      <span>Halaman {{ meta.current_page }} / {{ meta.last_page }}</span>
      <button class="btn out sm" :disabled="page >= meta.last_page" @click="page++; load()">Berikutnya</button>
    </div>

    <ItemForm v-if="showForm" :item="editing" :errors="formErrors" :saving="saving" @save="save" @cancel="showForm = false" />
  </main>
</template>

<style scoped>
main { max-width: 1100px; margin: 20px auto; padding: 0 16px; }
.toolbar { display: flex; gap: 10px; flex-wrap: wrap; }
.toolbar select { width: auto; }
.grow { flex: 1; min-width: 180px; }
.info { margin: 14px 0; color: var(--mu); }
.ok { color: var(--g); }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px; }
.card { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px #0002; }
.card img { width: 100%; aspect-ratio: 1; object-fit: cover; background: #e5e7eb; }
.body { padding: 10px; display: grid; gap: 5px; }
.name { font-size: 14px; line-height: 1.3; min-height: 36px; }
.price { font-weight: 700; font-size: 16px; }
.meta { font-size: 12px; color: var(--mu); }
.tag { display: inline-block; font-size: 11px; background: #e8f7e9; color: var(--g); border-radius: 4px; padding: 1px 6px; }
.tag.sold { background: #fee2e2; color: #dc2626; }
.actions { display: flex; gap: 6px; margin-top: 4px; }
.pager { display: flex; justify-content: center; align-items: center; gap: 14px; margin: 22px 0; }
</style>
