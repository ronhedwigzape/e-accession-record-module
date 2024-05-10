<template>
    <div class="tw-flex tw-flex-col tw-items-center tw-justify-center">
        <img src="../../../assets/avatar.png" alt="avatar" :style="`height: ${height}em`">
    </div>
</template>
<script setup>
  defineProps({
      height: Number
  })
</script>
