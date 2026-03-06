<template>
  <div class="p-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-gray-600 mt-1 font-medium">
        Selamat datang kembali, <span class="text-blue-600">{{ authStore.user?.name }}</span>!
      </p>
    </div>

    <div v-if="loading" class="flex justify-center items-center py-20">
      <p class="text-gray-500 font-medium animate-pulse">Memuat data statistik...</p>
    </div>

    <div v-else>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
          <div>
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Project Aktif</h3>
            <p class="text-4xl font-extrabold text-blue-600">{{ stats.activeProjects }}</p>
          </div>
          <div class="p-4 bg-blue-50 rounded-full">
            <span class="text-2xl">📁</span>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
          <div>
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Task Belum Selesai</h3>
            <p class="text-4xl font-extrabold text-orange-500">{{ stats.unfinishedTasks }}</p>
          </div>
          <div class="p-4 bg-orange-50 rounded-full">
            <span class="text-2xl">📝</span>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
          <h3 class="font-bold text-gray-800">Task Mendekati Deadline</h3>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-white border-b border-gray-100 text-sm text-gray-500">
                <th class="px-6 py-4 font-bold">Nama Task</th>
                <th class="px-6 py-4 font-bold">Project</th>
                <th class="px-6 py-4 font-bold">Due Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="upcomingTasks.length === 0">
                <td colspan="3" class="px-6 py-10 text-center text-gray-500 font-medium">
                  Tidak ada task yang mendekati deadline. Mantap! 🎉
                </td>
              </tr>
              
              <tr 
                v-for="task in upcomingTasks" 
                :key="task.id" 
                class="border-b border-gray-50 hover:bg-slate-50 transition-colors"
              >
                <td class="px-6 py-4 font-semibold text-gray-800">{{ task.title }}</td>
                <td class="px-6 py-4 text-gray-600">{{ task.project?.name }}</td>
                <td class="px-6 py-4 text-red-600 font-bold">
                  {{ task.due_date }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import api from '../src/services/api'; // Pastikan path ke file axios kamu sudah benar

// Inisialisasi Auth Store untuk mengambil nama user
const authStore = useAuthStore();

// Tipe Data TypeScript
interface Task {
  id: number;
  title: string;
  due_date: string;
  project: {
    name: string;
  };
}

// State untuk memegang data API
const loading = ref(true);
const stats = ref({
  activeProjects: 0,
  unfinishedTasks: 0
});
const upcomingTasks = ref<Task[]>([]);

// Fungsi mengambil data dari API Laravel
const fetchDashboard = async () => {
  try {
    const response = await api.get('/dashboard');
    const data = response.data.data;
    
    // Mapping data response ke state
    stats.value.activeProjects = data.total_active_projects;
    stats.value.unfinishedTasks = data.total_unfinished_tasks;
    upcomingTasks.value = data.upcoming_tasks;
  } catch (error) {
    console.error("Gagal mengambil data dashboard:", error);
  } finally {
    loading.value = false;
  }
};

// Panggil fungsi saat halaman pertama kali dibuka
onMounted(() => {
  fetchDashboard();
});
</script>