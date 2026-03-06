<template>
  <div class="p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Daftar Project</h1>
        <p class="text-gray-500 mt-1">Kelola semua project dan statusnya di sini.</p>
      </div>
      
      <button 
        @click="showAddModal = true" 
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2"
      >
        <span>+</span> Tambah Project
      </button>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4">
      <div class="flex-1">
        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cari Project</label>
        <input 
          type="text" 
          v-model="searchQuery" 
          @input="handleSearch"
          placeholder="Ketik nama project..." 
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
        />
      </div>
      <div class="w-full md:w-64">
        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Filter Status</label>
        <select 
          v-model="statusFilter" 
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white"
        >
          <option value="all">Semua Status</option>
          <option value="active">Active</option>
          <option value="archived">Archived</option>
        </select>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div v-if="loading" class="p-10 text-center text-gray-500">
        Memuat data project...
      </div>
      
      <div v-else-if="error" class="p-10 text-center text-red-500">
        {{ error }}
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-sm text-gray-600">
              <th class="px-6 py-4 font-bold">Nama Project</th>
              <th class="px-6 py-4 font-bold">Deskripsi</th>
              <th class="px-6 py-4 font-bold">Status</th>
              <th class="px-6 py-4 font-bold">Dibuat Pada</th>
              <th class="px-6 py-4 font-bold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="filteredProjects.length === 0">
              <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                Tidak ada project yang ditemukan.
              </td>
            </tr>
            <tr 
              v-for="project in filteredProjects" 
              :key="project.id" 
              class="border-b border-gray-50 hover:bg-slate-50"
            >
              <td class="px-6 py-4 font-bold text-gray-800">{{ project.name }}</td>
              <td class="px-6 py-4 text-gray-600 text-sm max-w-xs truncate" :title="project.description">
                {{ project.description }}
              </td>
              <td class="px-6 py-4">
                <span 
                  :class="{
                    'bg-green-100 text-green-700 border-green-200': project.status === 'active',
                    'bg-gray-100 text-gray-700 border-gray-200': project.status === 'archived'
                  }"
                  class="px-3 py-1 rounded-full text-xs font-bold border"
                >
                  {{ project.status.toUpperCase() }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ new Date(project.created_at).toLocaleDateString('id-ID') }}
              </td>
              <td class="px-6 py-4 text-center">
                <router-link 
                  :to="`/projects/${project.id}`" 
                  class="text-blue-600 hover:text-blue-800 font-bold text-sm bg-blue-50 px-3 py-1.5 rounded-lg transition-colors"
                >
                  Kelola Task &rarr;
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <ProjectModal 
      :show="showAddModal" 
      :is-submitting="isSubmitting" 
      :errors="formErrors" 
      @close="closeModal" 
      @submit="submitProject" 
    />

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import api from '../src/services/api';

// 1. IMPORT KOMPONEN MODALNYA DI SINI
import ProjectModal from '../components/ProjectModal.vue';

// Types
interface Project {
  id: number;
  name: string;
  description: string;
  status: string;
  created_at: string;
}

// States
const projects = ref<Project[]>([]);
const loading = ref(true);
const error = ref('');

// Filter & Search States
const searchQuery = ref('');
const statusFilter = ref('all');
let searchTimeout: ReturnType<typeof setTimeout>;

// Modal States
const showAddModal = ref(false);
const isSubmitting = ref(false);
const formErrors = ref<Record<string, string[]>>({});

// Fetch API
const fetchProjects = async () => {
  loading.value = true;
  try {
    const response = await api.get(`/projects?search=${searchQuery.value}`);
    projects.value = response.data.data || response.data; 
  } catch (err: any) {
    error.value = 'Gagal memuat daftar project.';
  } finally {
    loading.value = false;
  }
};

// Fungsi Debounce untuk pencarian
const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchProjects();
  }, 500);
};

// Computed property untuk filter status
const filteredProjects = computed(() => {
  if (statusFilter.value === 'all') return projects.value;
  return projects.value.filter(p => p.status === statusFilter.value);
});

// Fungsi Tutup Modal
const closeModal = () => {
  showAddModal.value = false;
  formErrors.value = {};
};

// 2. UBAH FUNGSI SUBMIT AGAR MENERIMA DATA DARI MODAL
const submitProject = async (formData: { name: string; description: string; status: string }) => {
  isSubmitting.value = true;
  formErrors.value = {};
  
  try {
    // Gunakan formData yang dikirim dari komponen anak
    await api.post('/projects', formData); 
    closeModal();
    fetchProjects(); 
  } catch (err: any) {
    if (err.response?.status === 422) {
      formErrors.value = err.response.data.errors;
    } else {
      alert('Terjadi kesalahan pada server saat menyimpan project.');
    }
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  fetchProjects();
});
</script>