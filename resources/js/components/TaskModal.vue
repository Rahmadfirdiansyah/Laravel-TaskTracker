<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
      <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Task' : 'Tambah Task Baru' }}</h2>
      
      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-1">Judul Task</label>
          <input v-model="form.title" type="text" required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500"/>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
          <select v-model="form.category_id" required class="w-full px-4 py-2 border rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-500">
            <option value="" disabled>-- Pilih Kategori --</option>
            <option v-for="cat in categories" :key="cat.id || cat.id_category" :value="cat.id || cat.id_category">
              {{ cat.name || cat.nama || cat.nama_kategori }}
            </option>
          </select>
          <p v-if="categories.length === 0" class="text-xs text-red-500 mt-1">⚠️ Tidak ada kategori ditemukan di API</p>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
          <textarea v-model="form.description" required rows="2" class="w-full px-4 py-2 border rounded-lg outline-none"></textarea>
        </div>

        <div class="mb-4 flex gap-4">
          <div class="flex-1">
            <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
            <select v-model="form.status" class="w-full px-4 py-2 border rounded-lg bg-white">
              <option value="todo">To Do</option>
              <option value="in_progress">In Progress</option>
              <option value="done">Done</option>
            </select>
          </div>
          <div class="flex-1">
            <label class="block text-sm font-bold text-gray-700 mb-1">Due Date</label>
            <input v-model="form.due_date" type="date" required class="w-full px-4 py-2 border rounded-lg outline-none"/>
          </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
          <button type="button" @click="$emit('close')" class="px-4 py-2 text-gray-600">Batal</button>
          <button type="submit" :disabled="loading" class="px-4 py-2 bg-blue-600 text-white rounded-lg disabled:opacity-50">
            {{ loading ? 'Menyimpan...' : 'Simpan Task' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
  isOpen: boolean;
  isEditing: boolean;
  initialData: any;
  categories: any[];
  loading: boolean;
}>();

const emit = defineEmits(['close', 'submit']);

const form = ref({ ...props.initialData });

// Sync data saat modal dibuka atau initialData berubah
watch(() => props.initialData, (newVal) => {
  form.value = { ...newVal };
}, { deep: true });

const handleSubmit = () => {
  emit('submit', form.value);
};
</script>