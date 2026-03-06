<template>
  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
      <h2 class="text-2xl font-bold text-gray-900">Task<span class="text-blue-600">Tracker</span></h2>
      <p class="text-gray-600 mb-6 font-medium">Masuk Ke akun Kamu</p>
      
      <div v-if="generalError" class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm font-medium">
        {{ generalError }}
      </div>

      <form class="space-y-4" @submit.prevent="handleLogin">
        <div>
          <label class="block text-sm text-gray-700 mb-1 font-bold" for="email">Email</label>
          <input 
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
            :class="{'border-red-500': errors.email, 'border-gray-300': !errors.email}"
            type="email" 
            v-model="email" 
            id="email" 
            required 
          />
          <p v-if="errors.email" class="text-red-500 text-xs mt-1 font-medium">{{ errors.email[0] }}</p>
        </div>
        
        <div>
          <label class="block text-sm text-gray-700 mb-1 font-bold" for="password">Password</label>
          <input 
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
            :class="{'border-red-500': errors.password, 'border-gray-300': !errors.password}"
            type="password" 
            v-model="password" 
            id="password" 
            required 
          />
          <p v-if="errors.password" class="text-red-500 text-xs mt-1 font-medium">{{ errors.password[0] }}</p>
        </div>
        
        <div class="flex justify-center py-4">
          <Button class="w-full" type="submit" :disabled="loading">
            {{ loading ? 'Tunggu...' : 'Masuk' }}
          </Button>
        </div>
      </form>

      <Loader :show="loading" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import Button from '../components/Button.vue';
import Loader from '../components/Loader.vue';
import axios from 'axios'; // Pastikan import axios untuk ngecek tipe error

const router = useRouter();
const authStore = useAuthStore();

const email = ref('');
const password = ref('');
const loading = ref(false);

// State untuk memisahkan error general dan error per-field
const generalError = ref('');
const errors = ref<Record<string, string[]>>({});

const handleLogin = async () => {
  loading.value = true;
  generalError.value = '';
  errors.value = {}; // Reset error tiap kali tombol ditekan

  try {
    // Pastikan authStore kamu menerima 2 parameter (email, password) atau berbentuk object
    await authStore.login(email.value, password.value); 
    router.push('/dashboard');
  } catch (err: any) {
    if (axios.isAxiosError(err) && err.response) {
      // Tangkap error 422 dari validasi Laravel
      if (err.response.status === 422) {
        errors.value = err.response.data.errors;
      } 
      // Tangkap error 401 saat kredensial salah
      else if (err.response.status === 401) {
        generalError.value = err.response.data.message;
      } else {
        generalError.value = 'Terjadi kesalahan pada server.';
      }
    } else {
      generalError.value = 'Gagal terhubung ke server.';
    }
  } finally {
    loading.value = false;
  }
};
</script>