<script setup lang="ts">
const count = 0
</script>

<template>
    <div class="flex items-center gap-4">
        <span class="badge badge-soft badge-accent">
            {{ count }}
        </span>
        <button class="btn btn-sm btn-primary" @click="count++" type="button">
            Click me
        </button>
    </div>
</template>
