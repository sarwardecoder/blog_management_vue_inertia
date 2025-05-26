<template>
  <div class="container mt-5 text-center">
    <button
      @click="toggleColor"
      :style="{ color: textColor }"
      class="btn btn-outline-dark fw-bold fs-4 px-5 py-2"
    >
      {{ label }}
    </button>
  </div>
</template>
  
  <script setup>
  import { ref } from 'vue'
  
  const label = ref('Feedback')
  const textColor = ref('red')
  
  function toggleColor() {
    if (label.value === 'RED') {
      label.value = 'GREEN'
      textColor.value = 'yellow'
    } else {
      label.value = 'RED'
      textColor.value = 'red'
    }
  }
  </script>
  