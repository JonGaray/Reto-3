<template>
  <div class="container mt-4">
    <Header />
    <Panel v-if="!showUsers" @show-users="handleShowUsers" />
    <Users v-if="showUsers" :actionId="selectedActionId" @hide-users="handleHideUsers" />
  </div>
</template>

<script setup>
import { ref } from "vue";
import Header from "./HeaderAdmin.vue";
import Users from "./ActionRegister.vue";
import Panel from "./AdminPanel.vue";

const showUsers = ref(false);
const selectedActionId = ref(null);

const handleShowUsers = (actionId) => {
  selectedActionId.value = actionId;
  showUsers.value = true;
};

const handleHideUsers = () => {
  selectedActionId.value = null;
  showUsers.value = false;
};
</script>

<style scoped>
</style>
