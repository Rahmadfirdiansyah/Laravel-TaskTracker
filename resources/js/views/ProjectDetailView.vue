<template>
  <div class="p-8">
    <button @click="router.push('/projects')" class="mb-4 text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2">
      &larr; Kembali ke Daftar Project
    </button>

    <div v-if="loading" class="flex justify-center py-20">
      <p class="text-gray-500 animate-pulse font-medium">Memuat detail project dan task...</p>
    </div>

    <div v-else-if="project">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex-1">
          <div class="flex items-center gap-3 mb-2">
            <h1 class="text-3xl font-bold text-gray-900">{{ project.name }}</h1>
            <span :class="project.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded-full text-xs font-bold border uppercase">
              {{ project.status }}
            </span>
          </div>
          <p class="text-gray-600 mb-2">{{ project.description }}</p>
        </div>
      </div>

      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Board Task</h2>
        <button @click="openTaskModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium shadow-sm transition-all active:scale-95">
          + Tambah Task
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        <div v-for="col in columns" :key="col.id" 
             :class="['rounded-xl border p-4 shadow-sm', col.bgColor, col.borderColor]">
          
          <h3 :class="['font-bold mb-4 flex items-center justify-between uppercase text-sm tracking-wider', col.textColor]">
            {{ col.label }}
            <span class="bg-white px-2 py-0.5 rounded-full text-xs shadow-sm border">{{ getTasksByStatus(col.id).length }}</span>
          </h3>

          <div class="space-y-3 min-h-[100px]">
            <TaskCard v-for="task in getTasksByStatus(col.id)" :key="task.id" :task="task" @edit="openTaskModal(task)" @delete="deleteTask(task.id)" />
            <p v-if="getTasksByStatus(col.id).length === 0" class="text-center text-gray-400 text-sm py-8 italic border-2 border-dashed rounded-lg">Kosong</p>
          </div>
        </div>
      </div>

      <div v-if="unmappedTasks.length > 0" class="mt-10 p-4 bg-amber-50 border border-amber-200 rounded-lg">
        <p class="text-amber-800 font-bold text-sm mb-2">⚠️ Ada {{ unmappedTasks.length }} Task dengan status tidak dikenal (Muncul di sini):</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <TaskCard v-for="task in unmappedTasks" :key="task.id" :task="task" @edit="openTaskModal(task)" @delete="deleteTask(task.id)" />
        </div>
      </div>
    </div>

        <TaskModal 
      :is-open="showTaskModal" 
      :is-editing="isEditing" 
      :initial-data="taskForm" 
      :categories="categories" 
      :loading="isSubmitting" 
      @close="closeTaskModal" 
      @submit="submitTask" 
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../src/services/api';
import TaskCard from '../components/TaskCard.vue';
import TaskModal from '../components/TaskModal.vue';

const route = useRoute();
const router = useRouter();
const projectId = route.params.id;

// Config Columns
const columns = [
  { id: 'todo', label: 'To Do', bgColor: 'bg-gray-50', borderColor: 'border-gray-200', textColor: 'text-gray-700' },
  { id: 'in_progress', label: 'In Progress', bgColor: 'bg-blue-50', borderColor: 'border-blue-100', textColor: 'text-blue-800' },
  { id: 'done', label: 'Done', bgColor: 'bg-green-50', borderColor: 'border-green-100', textColor: 'text-green-800' }
];

// States
const loading = ref(true);
const project = ref<any>(null);
const tasks = ref<any[]>([]);
const categories = ref<any[]>([]);
const showTaskModal = ref(false);
const isEditing = ref(false);
const isSubmitting = ref(false);

const taskForm = ref({
  id: null,
  title: '',
  description: '',
  status: 'todo',
  due_date: '',
  category_id: null as number | null,
  project_id: projectId,
});

// GET TASKS BY STATUS (Dibuat fleksibel)
const getTasksByStatus = (status: string) => {
  return tasks.value.filter(t => {
    const s = String(t.status).toLowerCase();
    if (status === 'todo') return s === 'todo' || s === 'pending';
    if (status === 'in_progress') return s === 'in_progress';
    if (status === 'done') return s === 'done' || s === 'completed';
    return false;
  });
};

// Task yang tidak masuk kategori manapun
const unmappedTasks = computed(() => {
  const mappedStatus = ['todo', 'pending', 'in_progress', 'done', 'completed'];
  return tasks.value.filter(t => !mappedStatus.includes(String(t.status).toLowerCase()));
});

const fetchProjectData = async () => {
  loading.value = true;
  try {
    const [projRes, taskRes] = await Promise.all([
      api.get(`/projects/${projectId}`),
      api.get(`/tasks?project_id=${projectId}`)
    ]);
    project.value = projRes.data.data || projRes.data;
    tasks.value = taskRes.data.data || taskRes.data || [];
    console.log('[DEBUG] Tasks loaded:', tasks.value);
  } catch (error) {
    router.push('/projects');
  } finally {
    loading.value = false;
  }
};

const fetchCategories = async () => {
  try {
    const response = await api.get('/categories');
    const data = response.data.data || response.data;
    
    if (Array.isArray(data)) {
      categories.value = data;
    } else if (typeof data === 'object' && data !== null) {
      categories.value = Object.values(data); // Konversi objek jadi array
    } else {
      categories.value = [];
    }
  } catch (e) { console.error(e); }
};

const openTaskModal = (task: any = null) => {
  if (task) {
    isEditing.value = true;
    taskForm.value = {
      ...task,
      category_id: Number(task.category_id || (task.category?.id)),
      project_id: projectId
    };
  } else {
    isEditing.value = false;
    taskForm.value = { id: null, title: '', description: '', status: 'todo', due_date: new Date().toISOString().split('T')[0], category_id: null, project_id: projectId };
  }
  showTaskModal.value = true;
};

const closeTaskModal = () => { showTaskModal.value = false; };

const submitTask = async (formData: any) => {
  // Ganti taskForm.value dengan formData bawaan dari modal
  if (!formData.category_id) return alert('Pilih Kategori!');
  
  isSubmitting.value = true;
  try {
    const payload = { ...formData, category_id: Number(formData.category_id) };
    
    if (isEditing.value) {
      await api.put(`/tasks/${formData.id}`, payload);
    } else {
      await api.post('/tasks', payload);
    }
    
    closeTaskModal();
    await fetchProjectData(); // Refresh board otomatis
  } catch (error: any) {
    alert(error.response?.data?.message || 'Gagal menyimpan data!');
  } finally {
    isSubmitting.value = false;
  }
};

const deleteTask = async (id: number) => {
  if (confirm('Hapus task?')) {
    try { await api.delete(`/tasks/${id}`); await fetchProjectData(); } catch (e) { alert('Gagal'); }
  }
};

onMounted(() => { fetchProjectData(); fetchCategories(); });
</script>