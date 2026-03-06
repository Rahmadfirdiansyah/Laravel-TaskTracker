<template>
  <div class="project-list-view">
    <h1>Projects</h1>
    <Button @click="router.push('/')">Back to Dashboard</Button>
    <Loader :show="loading" text="Loading projects..." />
    <div v-if="error">{{ error }}</div>
    <ul v-else-if="!loading">
      <li v-for="project in projects" :key="project.id">
        {{ project.name }}
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../src/services/api';
import type { Project } from '../types';
import Button from '../components/Button.vue';
import Loader from '../components/Loader.vue';

const router = useRouter();
const projects = ref<Project[]>([]);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
  try {
    const response = await api.get('/projects');
    projects.value = response.data;
  } catch (err: any) {
    error.value = 'Failed to load projects';
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.project-list-view {
  padding: 20px;
}
</style>