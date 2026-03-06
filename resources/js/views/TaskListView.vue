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
            <span 
              :class="project.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
              class="px-3 py-1 rounded-full text-xs font-bold border"
            >
              {{ project.status.toUpperCase() }}
            </span>
          </div>
          <p class="text-gray-600 mb-2">{{ project.description }}</p>
          <p class="text-xs text-gray-400 font-medium">
            Dibuat pada: {{ new Date(project.created_at).toLocaleDateString('id-ID') }}
          </p>
        </div>

        <div class="flex gap-6 border-l border-gray-100 pl-6">
          <div class="text-center">
            <p class="text-xs font-bold text-gray-400 uppercase">Total Task</p>
            <p class="text-3xl font-black text-blue-600">{{ tasks.length }}</p>
          </div>
          <div class="text-center">
            <p class="text-xs font-bold text-gray-400 uppercase">Selesai</p>
            <p class="text-3xl font-black text-green-500">{{ doneTasks.length }}</p>
          </div>
        </div>
      </div>

      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Board Task</h2>
        <button 
          @click="openTaskModal()" 
          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm"
        >
          <span>+</span> Tambah Task
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
          <h3 class="font-bold text-gray-700 mb-4 flex items-center justify-between">
            To Do <span class="bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ todoTasks.length }}</span>
          </h3>
          <div class="space-y-3">
            <div v-for="task in todoTasks" :key="task.id" class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-gray-400 hover:shadow-md transition-shadow">
              <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ task.status }}</span>
              <h4 class="font-bold text-gray-800 mt-1">{{ task.title }}</h4>
              <p class="text-sm text-gray-600 mt-2 line-clamp-2" :title="task.description">{{ task.description }}</p>
              <div class="mt-4 flex items-center justify-between">
                <span class="text-xs font-medium text-red-500 border border-red-100 bg-red-50 px-2 py-1 rounded">
                  📅 {{ task.due_date }}
                </span>
                <div class="flex gap-2">
                  <button @click="openTaskModal(task)" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</button>
                  <button @click="deleteTask(task.id)" class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-blue-50 rounded-xl border border-blue-100 p-4">
          <h3 class="font-bold text-blue-800 mb-4 flex items-center justify-between">
            In Progress <span class="bg-blue-200 text-blue-800 px-2 py-0.5 rounded-full text-xs">{{ inProgressTasks.length }}</span>
          </h3>
          <div class="space-y-3">
            <div v-for="task in inProgressTasks" :key="task.id" class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
              <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">{{ task.status }}</span>
              <h4 class="font-bold text-gray-800 mt-1">{{ task.title }}</h4>
              <p class="text-sm text-gray-600 mt-2 line-clamp-2" :title="task.description">{{ task.description }}</p>
              <div class="mt-4 flex items-center justify-between">
                <span class="text-xs font-medium text-red-500 border border-red-100 bg-red-50 px-2 py-1 rounded">
                  📅 {{ task.due_date }}
                </span>
                <div class="flex gap-2">
                  <button @click="openTaskModal(task)" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</button>
                  <button @click="deleteTask(task.id)" class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-green-50 rounded-xl border border-green-100 p-4">
          <h3 class="font-bold text-green-800 mb-4 flex items-center justify-between">
            Done <span class="bg-green-200 text-green-800 px-2 py-0.5 rounded-full text-xs">{{ doneTasks.length }}</span>
          </h3>
          <div class="space-y-3">
            <div v-for="task in doneTasks" :key="task.id" class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow opacity-75">
              <span class="text-[10px] font-bold text-green-600 uppercase tracking-wider">{{ task.status }}</span>
              <h4 class="font-bold text-gray-800 mt-1 line-through">{{ task.title }}</h4>
              <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ task.description }}</p>
              <div class="mt-4 flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 px-2 py-1">
                  📅 {{ task.due_date }}
                </span>
                <div class="flex gap-2">
                  <button @click="openTaskModal(task)" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</button>
                  <button @click="deleteTask(task.id)" class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div v-if="showTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Task' : 'Tambah Task Baru' }}</h2>
        
        <form @submit.prevent="submitTask">
          <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Judul Task</label>
            <input v-model="taskForm.title" type="text" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"/>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
            <textarea v-model="taskForm.description" required rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
          </div>

          <div class="mb-4 flex gap-4">
            <div class="flex-1">
              <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
              <select v-model="taskForm.status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                <option value="todo">To Do</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
              </select>
            </div>
            <div class="flex-1">
              <label class="block text-sm font-bold text-gray-700 mb-1">Due Date</label>
              <input v-model="taskForm.due_date" type="date" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"/>
            </div>
          </div>

          <div class="flex justify-end gap-3 mt-6">
            <button type="button" @click="closeTaskModal" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Batal</button>
            <button type="submit" :disabled="isSubmitting" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium disabled:opacity-50">
              {{ isSubmitting ? 'Menyimpan...' : 'Simpan Task' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../src/services/api';

const route = useRoute();
const router = useRouter();
const projectId = route.params.id; // Ambil ID dari URL (/projects/1)

// States
const loading = ref(true);
const project = ref<any>(null);
const tasks = ref<any[]>([]);

// Modal States
const showTaskModal = ref(false);
const isEditing = ref(false);
const isSubmitting = ref(false);
const taskForm = ref({
  id: null,
  title: '',
  description: '',
  status: 'todo',
  due_date: '',
  project_id: projectId
});

// Computed Properties untuk Kanban Board
const todoTasks = computed(() => tasks.value.filter(t => t.status === 'todo'));
const inProgressTasks = computed(() => tasks.value.filter(t => t.status === 'in_progress'));
const doneTasks = computed(() => tasks.value.filter(t => t.status === 'done'));

// 1. Fetch Project & Tasks
const fetchProjectData = async () => {
  loading.value = true;
  try {
    // Ambil detail project
    const projRes = await api.get(`/projects/${projectId}`);
    project.value = projRes.data.data || projRes.data;

    // Ambil daftar task untuk project ini
    // (Asumsi backend kita mendukung filter ?project_id=)
    const taskRes = await api.get(`/tasks?project_id=${projectId}`);
    tasks.value = taskRes.data.data || taskRes.data || [];
  } catch (error) {
    alert('Gagal memuat data project.');
    router.push('/projects');
  } finally {
    loading.value = false;
  }
};

// 2. Buka Modal (Tambah atau Edit)
const openTaskModal = (task: any = null) => {
  if (task) {
    isEditing.value = true;
    taskForm.value = { ...task, project_id: projectId };
  } else {
    isEditing.value = false;
    taskForm.value = {
      id: null,
      title: '',
      description: '',
      status: 'todo',
      due_date: '',
      project_id: projectId
    };
  }
  showTaskModal.value = true;
};

// 3. Tutup Modal
const closeTaskModal = () => {
  showTaskModal.value = false;
};

// 4. Submit Task (Create / Update)
const submitTask = async () => {
  isSubmitting.value = true;
  try {
    if (isEditing.value) {
      // Update
      await api.put(`/tasks/${taskForm.value.id}`, taskForm.value);
    } else {
      // Create
      await api.post('/tasks', taskForm.value);
    }
    closeTaskModal();
    fetchProjectData(); // Refresh data
  } catch (error) {
    alert('Gagal menyimpan task.');
  } finally {
    isSubmitting.value = false;
  }
};

// 5. Delete Task
const deleteTask = async (taskId: number) => {
  if (confirm('Apakah kamu yakin ingin menghapus task ini?')) {
    try {
      await api.delete(`/tasks/${taskId}`);
      fetchProjectData(); // Refresh data
    } catch (error) {
      alert('Gagal menghapus task.');
    }
  }
};

onMounted(() => {
  fetchProjectData();
});
</script>