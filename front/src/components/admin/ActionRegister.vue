<template>
  <div v-if="actionId !== null" class="container mt-4">
    <table class="table table-bordered text-center">
      <thead class="table-light">
      <tr>
        <td class="colspan" colspan="4">
          <b>{{ actionName }}</b><br>
          Capacidad: <b>{{ actionCapacity }}</b>
        </td>
      </tr>
      <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Email</th>
        <th>DNI</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(user, index) in users" :key="user.user_id">
        <td><strong>{{ user.user_name }}</strong></td>
        <td>{{ user.user_lastname }}</td>
        <td>{{ user.user_email }}</td>
        <td>{{ user.user_dni }}</td>
      </tr>
      </tbody>
    </table>
    <div class="row my-0">
      <div class="col offset-11">
        <button class="btn btn-danger mt-3" @click="closeUsers">Volver</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, defineProps, watch } from "vue";

const users = ref([]);
const API_SERVER = import.meta.env.VITE_API_SERVER; //import de la URL desde el .env
const actionName = ref("");
const actionCapacity = ref("");

const props = defineProps(["actionId"]); //definicion de los parametros que recogemos por el componente

const fetchUsers = async () => {
  if (props.actionId === null) return;

  try {
    const response = await axios.get(`${API_SERVER}/api/user/${props.actionId}/action`); //llamada a la ruta API del backend
    users.value = response.data.data;

    if (users.value.length > 0) { //validaciones de que haya texto en los imputs
      actionName.value = users.value[0].action_name;
      actionCapacity.value = users.value[0].action_capacity;
    }
  } catch (error) {
    console.error("Error al obtener los usuarios:", error);
  }
};

watch(() => props.actionId, fetchUsers, { immediate: true });

const emits = defineEmits(["hide-users"]);

const closeUsers = () => {
  emits("hide-users");
};
</script>

<style scoped>
</style>
