<template>
  <div class="flex h-screen bg-gray-50 font-sans overflow-hidden">
    
    <Sidebar v-if="!isLoginPage" />

    <main class="flex-1 overflow-y-auto">
      <router-view></router-view>
    </main>
    
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import Sidebar from './components/Sidebar.vue';

const route = useRoute();

// Mengecek apakah rute yang sedang aktif bernama 'Login'
const isLoginPage = computed(() => route.name === 'Login');
</script>