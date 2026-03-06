<template>
  <div class="loader" v-if="show">
    <div class="spinner"></div>
    <span v-if="text">{{ text }}</span>
  </div>
</template>

<script setup lang="ts">
interface Props {
  show?: boolean;
  text?: string;
}

withDefaults(defineProps<Props>(), {
  show: true,
  text: '',
});
</script>

<style scoped>
.loader {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #007bff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>