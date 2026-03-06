<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
      <h2 class="text-xl font-bold mb-4">Buat Project Baru</h2>
      
      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-1">Nama Project</label>
          <input 
            v-model="form.name" 
            type="text" 
            required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
            :class="{'border-red-500': errors.name, 'border-gray-300': !errors.name}"
          />
          <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
          <textarea 
            v-model="form.description" 
            required
            rows="3"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
            :class="{'border-red-500': errors.description, 'border-gray-300': !errors.description}"
          ></textarea>
          <p v-if="errors.description" class="text-red-500 text-xs mt-1">{{ errors.description[0] }}</p>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
          <select 
            v-model="form.status" 
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white"
            :class="{'border-red-500': errors.status, 'border-gray-300': !errors.status}"
          >
            <option value="active">Active</option>
            <option value="archived">Archived</option>
          </select>
          <p v-if="errors.status" class="text-red-500 text-xs mt-1">{{ errors.status[0] }}</p>
        </div>

        <div class="flex justify-end gap-3">
          <button 
            type="button" 
            @click="$emit('close')" 
            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium transition-colors"
          >
            Batal
          </button>
          <button 
            type="submit" 
            :disabled="isSubmitting"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50"
          >
            {{ isSubmitting ? 'Menyimpan...' : 'Simpan Project' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
  show: boolean;
  isSubmitting: boolean;
  errors: Record<string, string[]>;
}>();

const emit = defineEmits(['close', 'submit']);

// State form lokal DITAMBAH status default 'active'
const form = ref({ name: '', description: '', status: 'active' });

// Reset form setiap kali modal dibuka, status dikembalikan ke 'active'
watch(() => props.show, (isOpen) => {
  if (isOpen) {
    form.value = { name: '', description: '', status: 'active' };
  }
});

const handleSubmit = () => {
  emit('submit', form.value);
};
</script>