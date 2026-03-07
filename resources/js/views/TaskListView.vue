<template>
  <div class="p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Semua Task Terdaftar</h1>
        <p class="text-gray-500 mt-1">Pantau dan cari seluruh tugas dari berbagai project di sini.</p>
      </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4">
      <div class="flex-1">
        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cari Tugas</label>
        <input 
          type="text" 
          v-model="searchQuery" 
          @input="handleSearch"
          placeholder="Ketik judul tugas..." 
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
          <option value="todo">To Do</option>
          <option value="in_progress">In Progress</option>
          <option value="done">Done</option>
        </select>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div v-if="loading" class="p-10 text-center text-gray-500 animate-pulse">
        Memuat seluruh data task...
      </div>
      
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-sm text-gray-600">
              <th class="px-6 py-4 font-bold">Judul Task</th>
              <th class="px-6 py-4 font-bold">Dari Project</th>
              <th class="px-6 py-4 font-bold">Kategori</th>
              <th class="px-6 py-4 font-bold">Status</th>
              <th class="px-6 py-4 font-bold">Tenggat Waktu</th>
              <th class="px-6 py-4 font-bold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="filteredTasks.length === 0">
              <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                Tidak ada task yang ditemukan.
              </td>
            </tr>
            
            <tr 
              v-for="task in filteredTasks" 
              :key="task.id" 
              class="border-b border-gray-50 hover:bg-slate-50 transition-colors"
            >
              <td class="px-6 py-4 font-bold text-gray-800">{{ task.title }}</td>
              
              <td class="px-6 py-4 text-sm">
                <span v-if="task.project" class="font-medium bg-blue-50 text-blue-600 px-2 py-1 rounded-md border border-blue-100">
                  {{ task.project.name }}
                </span>
                <span v-else class="text-gray-400 italic">Tanpa Project</span>
              </td>
              
              <td class="px-6 py-4">
                <span v-if="task.category" class="text-[10px] font-bold bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded uppercase tracking-wider">
                  {{ task.category.name }}
                </span>
                <span v-else class="text-[10px] text-gray-400 italic">-</span>
              </td>
              
              <td class="px-6 py-4">
                <span 
                  class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                  :class="{
                    'bg-gray-100 text-gray-600 border border-gray-200': String(task.status).toLowerCase() === 'todo',
                    'bg-blue-100 text-blue-700 border border-blue-200': String(task.status).toLowerCase() === 'in_progress',
                    'bg-green-100 text-green-700 border border-green-200': String(task.status).toLowerCase() === 'done'
                  }"
                >
                  {{ String(task.status || 'todo').replace('_', ' ') }}
                </span>
              </td>
              
              <td class="px-6 py-4 text-sm font-medium" :class="task.status === 'done' ? 'text-gray-400' : 'text-red-500'">
                📅 {{ task.due_date }}
              </td>
              
              <td class="px-6 py-4 text-center">
                <router-link 
                  v-if="task.project_id"
                  :to="`/projects/${task.project_id}`" 
                  class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg transition-colors inline-block"
                >
                  Buka Project &rarr;
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import api from '../src/services/api';

// States
const tasks = ref<any[]>([]);
const loading = ref(true);

// Filter & Search
const searchQuery = ref('');
const statusFilter = ref('all');
let searchTimeout: ReturnType<typeof setTimeout>;

// Fetching Data (Tanpa project_id agar mengambil semua task)
const fetchAllTasks = async () => {
  loading.value = true;
  try {
    const response = await api.get(`/tasks?search=${searchQuery.value}`);
    tasks.value = response.data.data || response.data;
  } catch (error) {
    console.error('Gagal mengambil data task global', error);
  } finally {
    loading.value = false;
  }
};

// Fungsi Debounce untuk Pencarian
const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchAllTasks();
  }, 500);
};

// Filter Status secara Lokal
const filteredTasks = computed(() => {
  if (statusFilter.value === 'all') return tasks.value;
  return tasks.value.filter(t => String(t.status || 'todo').toLowerCase() === statusFilter.value);
});

onMounted(() => {
  fetchAllTasks();
});
</script>