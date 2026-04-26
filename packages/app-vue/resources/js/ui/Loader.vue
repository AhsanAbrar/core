<template>
  <div :style="{ width: size, height: size }" class="relative">
    <svg
      :class="color"
      class="animate-rotate absolute inset-0 origin-center"
      viewBox="25 25 50 50"
    >
      <circle
        class="path"
        cx="50"
        cy="50"
        r="20"
        fill="none"
        :stroke-width="stroke"
        stroke-miterlimit="10"
        stroke="currentColor"
      />
    </svg>
  </div>
</template>

<script setup lang="ts">
  const {
    color = 'text-gray-500',
    size = '40px',
    stroke = 4,
  } = defineProps<{
    color?: string
    size?: string
    stroke?: number
  }>()
</script>

<style scoped>
  .animate-rotate {
    animation: rotate 2s linear infinite;
  }

  .path {
    stroke-dasharray: 1, 200;
    stroke-dashoffset: 0;
    animation: dash 1.5s ease-in-out infinite;
    stroke-linecap: round;
  }

  @keyframes rotate {
    100% {
      transform: rotate(360deg);
    }
  }

  @keyframes dash {
    0% {
      stroke-dasharray: 1, 200;
      stroke-dashoffset: 0;
    }
    50% {
      stroke-dasharray: 89, 200;
      stroke-dashoffset: -35px;
    }
    100% {
      stroke-dasharray: 89, 200;
      stroke-dashoffset: -124px;
    }
  }
</style>
