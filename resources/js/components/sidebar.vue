<template>
  <aside class="w-64 bg-slate-900 text-white flex flex-col min-h-screen shadow-xl">
    <div class="p-6 border-b border-slate-800">
      <h2 class="text-2xl font-bold">Task<span class="text-blue-500">Tracker</span></h2>
    </div>

    <nav class="flex-1 p-4 space-y-2">
      <router-link 
        to="/dashboard" 
        class="block px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors font-medium"
        active-class="bg-blue-600 hover:bg-blue-700"
      >
        📊 Dashboard
      </router-link>
      
      <router-link 
        to="/projects" 
        class="block px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors font-medium"
        active-class="bg-blue-600 hover:bg-blue-700"
      >
        📁 Projects
      </router-link>

            <router-link 
        to="/task" 
        class="block px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors font-medium"
        active-class="bg-blue-600 hover:bg-blue-700"
      >
        ✅ Tasks
      </router-link>

    </nav>

    <div class="p-4 border-t border-slate-800">
      <button 
        @click="handleLogout" 
        class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors font-medium flex items-center justify-center gap-2"
      >
        Logout
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const handleLogout = () => {
  // Panggil fungsi logout dari Pinia
  authStore.logout();
  // Lempar balik ke halaman login
  router.push('/login');
};
</script>