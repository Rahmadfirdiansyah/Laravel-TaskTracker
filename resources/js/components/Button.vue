<template>
  <button
    :class="buttonClass"
    :disabled="disabled"
    @click="$emit('click')"
  >
    <slot></slot>
  </button>
</template>

<script setup lang="ts">
import { computed } from "vue"

interface Props {
  variant?: "primary" | "secondary" | "danger"
  size?: "small" | "medium" | "large"
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: "primary",
  size: "medium",
  disabled: false,
})

const baseClass =
  "rounded-md font-medium transition disabled:cursor-not-allowed disabled:opacity-60"

const variantClass = {
  primary: "bg-blue-600 text-white hover:bg-blue-700",
  secondary: "bg-gray-600 text-white hover:bg-gray-700",
  danger: "bg-red-600 text-white hover:bg-red-700",
}

const sizeClass = {
  small: "text-xs px-3 py-1.5",
  medium: "text-sm px-4 py-2",
  large: "text-base px-5 py-3",
}

const buttonClass = computed(() => [
  baseClass,
  variantClass[props.variant],
  sizeClass[props.size],
])
</script>