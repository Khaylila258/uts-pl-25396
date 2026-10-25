<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({ item: Object, errors: { type: Object, default: () => ({}) }, saving: Boolean })
const emit = defineEmits(['save', 'cancel'])

const blank = {
  name: '', description: '', price: 0, stock: 0,
  category: 'Makanan', image_url: '', origin_city: '', status: 'available',
}
const form = reactive({ ...blank })

// Isi form saat mode ubah; kosongkan saat mode tambah.
watch(() => props.item, (v) => Object.assign(form, blank, v || {}), { immediate: true })

function submit() {
  const body = { ...form, price: Number(form.price), stock: Number(form.stock) }
  if (!body.image_url) delete body.image_url
  emit('save', body)
}
</script>

<template>
  <div class="overlay" @click.self="emit('cancel')">
    <form class="modal" @submit.prevent="submit">
      <h3>{{ item ? 'Ubah Barang' : 'Tambah Barang' }}</h3>

      <label>Nama barang<input v-model="form.name" /></label>
      <p v-if="errors.name" class="err">{{ errors.name[0] }}</p>

      <label>Deskripsi<textarea v-model="form.description" rows="2"></textarea></label>

      <div class="row">
        <label>Harga (Rp)<input v-model="form.price" type="number" min="0" /></label>
        <label>Stok<input v-model="form.stock" type="number" min="0" /></label>
      </div>
      <p v-if="errors.price" class="err">{{ errors.price[0] }}</p>
      <p v-if="errors.stock" class="err">{{ errors.stock[0] }}</p>

      <div class="row">
        <label>Kategori
          <select v-model="form.category">
            <option>Makanan</option><option>Fashion</option><option>Kecantikan</option><option>Aksesoris</option>
          </select>
        </label>
        <label>Kota asal<input v-model="form.origin_city" /></label>
      </div>
      <p v-if="errors.origin_city" class="err">{{ errors.origin_city[0] }}</p>

      <label>URL gambar<input v-model="form.image_url" placeholder="https://..." /></label>
      <p v-if="errors.image_url" class="err">{{ errors.image_url[0] }}</p>

      <label>Status
        <select v-model="form.status"><option value="available">available</option><option value="sold">sold</option></select>
      </label>

      <div class="actions">
        <button type="button" class="btn out" @click="emit('cancel')">Batal</button>
        <button class="btn" :disabled="saving">{{ saving ? 'Menyimpan...' : 'Simpan' }}</button>
      </div>
    </form>
  </div>
</template>

<style scoped>
.overlay { position: fixed; inset: 0; background: #0006; display: grid; place-items: center; z-index: 10; padding: 16px; }
.modal { background: #fff; border-radius: 12px; padding: 22px; width: 100%; max-width: 460px; max-height: 92vh; overflow: auto; display: grid; gap: 8px; }
label { display: grid; gap: 4px; font-size: 13px; color: var(--mu); }
.row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 6px; }
</style>
