<template>
  <div class="container mt-4" v-if="showPanel">
    <table class="table table-bordered text-center">
      <thead class="table-light">
      <tr>
        <th>Actividad</th>
        <th>Centro</th>
        <th>Ubicación</th>
        <th>Fecha</th>
        <th>Precio</th>
        <th>Plazas</th>
        <th>Acciones</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(action, index) in actions" :key="action.id">
        <td><strong>{{ action.name }}</strong></td>
        <td>{{ action.center_name }}</td>
        <td>{{ action.center_address }}</td>
        <td>{{ action.date_init }}</td>
        <td>{{ action.price }}€</td>
        <td>{{ action.capacity }}</td>
        <td class="botones">
          <button class="btn btn-sm btn-outline-success" @click="viewUsers(action.id)">
            <img src="../../assets/img/grupo.png" alt="inscritos">
          </button>
          <button class="btn btn-sm btn-outline-warning" @click="editAction(action)">
            <img src="../../assets/img/editar.png" alt="editar">
          </button>
          <button class="btn btn-sm btn-outline-danger" @click="deleteAction(action.id)">
            <img src="../../assets/img/goma-de-borrar.png" alt="borrar">
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Modal de Edición -->
    <div v-if="showModal" class="modal-overlay col-10 offset-1 m-0" @click="closeModal">
      <div class="modal-content" @click.stop>
        <h4 class="d-flex justify-content-center mt-2">Editar Acción</h4>
        <form @submit.prevent="saveAction">
          <div class="form-group col-8 offset-2">
            <label for="name"><b>Nombre</b></label>
            <input v-model="selectedAction.name" id="name" type="text" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="date_init"><b>Fecha</b></label>
            <input v-model="selectedAction.date_init" id="date_init" type="date" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="price"><b>Precio</b></label>
            <input v-model="selectedAction.price" id="price" type="number" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="capacity"><b>Plazas</b></label>
            <input v-model="selectedAction.capacity" id="capacity" type="number" class="form-control" required />
          </div>
          <div class="form-group d-flex justify-content-center mb-2 gap-3 col-8 offset-2">
            <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
            <button type="button" class="btn btn-danger mt-3" @click="closeModal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import {ref, defineEmits, onMounted} from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const actions = ref([]);
const API_SERVER = import.meta.env.VITE_API_SERVER;
const showPanel = ref(true);
const showModal = ref(false);
const selectedAction = ref({});

const emits = defineEmits(["show-users"]);

const fetchActions = async () => {
  try {
    const response = await axios.get(`${API_SERVER}/api/action/center`);
    actions.value = response.data.data;
  } catch (error) {
    console.error("Error al obtener las acciones:", error);
  }
};

const editAction = (action) => {
  selectedAction.value = {
    ...action,
    date_init: action.date_init ? action.date_init.split("T")[0] : "",
  };
  showModal.value = true;
};

// Función para cerrar el modal
const closeModal = () => {
  showModal.value = false;
};

// Guardar cambios de la acción editada
const saveAction = async () => {
  let formattedStartTime = selectedAction.value.start_time;
  console.log("Fecha original:", formattedStartTime);

  if (formattedStartTime && formattedStartTime.length === 8) {
    const today = new Date().toISOString().split('T')[0];
    formattedStartTime = `${today}T${formattedStartTime}`;
  }

  const dataToSend = {
    ...selectedAction.value,
    start_time: formattedStartTime,
  };

  try {
    const response = await axios.put(
        `${API_SERVER}/api/action/${selectedAction.value.id}/update`,
        dataToSend,
        {
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        }
    );

    console.log(response.data);
    Swal.fire({
      timer: 1500,
      showConfirmButton: false,
      icon: "success",
      title: "Acción actualizada con éxito"
    });
    fetchActions();
    closeModal();
  } catch (error) {
    console.error("Error al actualizar la acción:", error.response?.data || error);
    Swal.fire({
      confirmButtonColor: "#198754",
      confirmButtonText: "Cerrar",
      icon: "error",
      title: "Hubo un error al actualizar la acción"
    });
  }
};

// Eliminar acción
const deleteAction = async (id) => {
  Swal.fire({
    title: "¿Estás seguro que deseas eliminar esta acción?",
    text: "No podrás deshacer esta decisión",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminarlo!",
    confirmButtonColor: "#198754",
    cancelButtonColor: "#d50606",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        await axios.delete(`${API_SERVER}/api/action/${id}/destroy`);
        await fetchActions();
        await Swal.fire({
          icon: "success",
          title: "Acción eliminada con éxito",
          showConfirmButton: false,
          timer: 1500});
      } catch (error) {
        console.error("Error al eliminar la acción:", error);
      }
    }
  });
};

const viewUsers = (actionId) => {
  emits("show-users", actionId);
  showPanel.value = false;
};

onMounted(fetchActions);
</script>

<style scoped>
.botones {
  display: flex;
  justify-content: center;
  gap: 8px;
}

button img {
  width: 18px;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 10px;
  max-width: 500px;
  width: 100%;
}
</style>
