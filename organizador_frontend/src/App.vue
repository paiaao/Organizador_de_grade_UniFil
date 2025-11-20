<script setup>
import { RouterView } from 'vue-router'
import { provide } from 'vue'
import api from './services/api' 

provide('api', api)
</script>

<template>
  <RouterView />
</template>