<script setup lang="ts">
const count = 0
</script>

<template>
    <div class="flex items-center gap-4">
        <span class="badge badge-soft badge-accent">{{ count }}</span>
        <button type="button" class="btn btn-sm btn-primary" @click="count++">Click me</button>
    </div>
</template>
