<template>
  <div class="dashboard-view">
    <h1>Dashboard</h1>
    <p>Welcome, {{ authStore.user?.name }}!</p>
    <Button @click="handleLogout" variant="danger">Logout</Button>
    <nav>
      <router-link to="/projects">Projects</router-link>
    </nav>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import Button from '../components/Button.vue';

const router = useRouter();
const authStore = useAuthStore();

const handleLogout = () => {
  authStore.logout();
  router.push('/login');
};
</script>

<style scoped>
.dashboard-view {
  padding: 20px;
}
</style>