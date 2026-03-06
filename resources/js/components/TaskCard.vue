<template>
  <div 
    class="bg-white p-4 rounded-lg shadow-sm border-l-4 hover:shadow-md transition-shadow relative" 
    :class="borderColorClass"
  >
    <div class="flex justify-between items-start mb-1">
      <span 
        class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-opacity-10" 
        :class="statusBgAndTextColorClass"
      >
        {{ task.status.replace('_', ' ') }}
      </span>
      
      <span 
        v-if="task.category" 
        class="text-[10px] font-bold bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded"
      >
        {{ task.category.name }}
      </span>
    </div>
    
    <h4 
      class="font-bold text-gray-800 mt-1" 
      :class="{'line-through text-gray-400': task.status === 'done'}"
    >
      {{ task.title }}
    </h4>
    
    <p class="text-sm text-gray-500 mt-2 line-clamp-2" :title="task.description">
      {{ task.description }}
    </p>
    
    <div class="mt-4 flex items-center justify-between">
      <span class="text-[11px] font-medium px-2 py-1 rounded flex items-center gap-1" :class="dateColorClass">
        <span>📅</span> {{ task.due_date }}
      </span>
      <div class="flex gap-3">
        <button @click="$emit('edit', task)" class="text-blue-600 hover:text-blue-800 text-xs font-bold uppercase tracking-tight">Edit</button>
        <button @click="$emit('delete', task.id)" class="text-red-500 hover:text-red-700 text-xs font-bold uppercase tracking-tight">Hapus</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

// 1. Update interface Task agar menyertakan object category
const props = defineProps<{
  task: {
    id: number;
    title: string;
    description: string;
    status: string;
    due_date: string;
    category?: {
      id: number;
      name: string;
    };
  }
}>();

defineEmits(['edit', 'delete']);

// --- Styling Dinamis ---
const borderColorClass = computed(() => {
  if (props.task.status === 'todo') return 'border-gray-300';
  if (props.task.status === 'in_progress') return 'border-blue-500';
  return 'border-green-500 bg-gray-50';
});

const statusBgAndTextColorClass = computed(() => {
  if (props.task.status === 'todo') return 'bg-gray-500 text-gray-600';
  if (props.task.status === 'in_progress') return 'bg-blue-500 text-blue-600';
  return 'bg-green-600 text-green-700';
});

const dateColorClass = computed(() => {
  if (props.task.status === 'done') return 'text-gray-400 bg-gray-100';
  return 'text-red-600 border border-red-100 bg-red-50';
});
</script>