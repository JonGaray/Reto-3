<template>
  <div class="container mb-3">
    <div class="row">
      <div class="col d-flex justify-content-between">
        <div class="align-items-start">
          <h4>Panel de Administrador</h4>
        </div>
        <div class="d-flex flex-row">
          <button class="btn btn-sm btn-outline-success boton  me-4   " @click="insertAction(action)">
            <img src="../../assets/img/anadir.png" alt="insert">
            Nueva Actividad</button>
          <button class="btn btn-sm btn-outline-success boton me-4   " @click="openCentroModal()">
            <img src="../../assets/img/anadir.png" alt="insert">
            Nuevo centro</button>
          <button class="btn btn-outline-success" @click="router.push({path:'/'})">Volver</button>

        </div>
      </div>

    </div>

    <div v-if="showModal" class="modal-overlay col-10 offset-1 mt-3" @click="closeModal">
      <div class="modal-content" @click.stop>
        <h4 class="d-flex justify-content-center mt-3">Insertar Acción</h4>
        <form @submit.prevent="saveAction">
          <div class="form-group col-8 offset-2">
            <label for="name"><b>Nombre</b></label>
            <input v-model="selectedAction.name" id="name" type="text" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="description"><b>Descripción</b></label>
            <textarea v-model="selectedAction.description" id="description" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="date_init"><b>Fecha de Inicio</b></label>
            <input v-model="selectedAction.date_init" id="date_init" type="date" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="date_end"><b>Fecha de Finalización</b></label>
            <input v-model="selectedAction.date_end" id="date_end" type="date" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="start_time"><b>Hora de Inicio</b></label>
            <input v-model="selectedAction.start_time" id="start_time" type="time" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="duration"><b>Duración (minutos)</b></label>
            <input v-model="selectedAction.duration" id="duration" type="number" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="price"><b>Precio</b></label>
            <input v-model="selectedAction.price" id="price" type="number" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="capacity"><b>Plazas</b></label>
            <input v-model="selectedAction.capacity" id="capacity" type="number" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="languaje"><b>Idioma</b></label>
            <input v-model="selectedAction.languaje" id="languaje" type="text" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="age"><b>Edad</b></label>
            <input v-model="selectedAction.age" id="age" type="number" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="category"><b>Categoría</b></label>
            <select v-model="selectedAction.category" id="category" class="form-select" required>
              <option value="cultura">Cultura</option>
              <option value="deportes">Deportes</option>
              <option value="educacion">Educación</option>
              <option value="medio ambiente">Medio Ambiente</option>
            </select>
          </div>
          <div class="form-group col-8 offset-2">
            <label for="center_id"><b>Centro Cívico</b></label>
            <select id="center_id" class="form-select" required v-model="selectedAction.center_id">
              <option v-for="center in centers" :key="center.id" :value="center.id">{{ center.name }}</option>
            </select>
          </div>
          <div class="form-group d-flex justify-content-center mb-2 gap-3 col-8 offset-2">
            <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
            <button class="btn btn-danger mt-3" @click="closeActionModal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>



    <div v-if="showModalCentro" class="modal-overlay col-10 offset-1 my-3" @click="closeCentroModal">
      <div class="modal-content" @click.stop>
        <h4>Crear centro</h4>
        <form @submit.prevent="saveCenter">
          <div class="form-group col-8 offset-2">
            <label for="name"><b>Nombre</b></label>
            <input v-model="nombreCentro" id="name" type="text" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="description"><b>Calle</b></label>
            <input type="text" v-model="calleCentro" id="calle" class="form-control" required />
          </div>
          <div class="d-flex justify-content-center gap-4">
            <div class="d-flex justify-content-center">
              <button type="submit" class="btn btn-success my-3" @click="insertarCentro()">Guardar Centro</button>
            </div>
            <div class="d-flex justify-content-center">
              <button type="button" class="btn btn-danger my-3" @click="closeCentroModal()">Cancelar</button>
            </div>
          </div>
        </form>
      </div>
    </div>



  </div>

</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import { useRouter } from "vue-router";

const API_SERVER = import.meta.env.VITE_API_SERVER;
const centers = ref([]);
const actions = ref([]); // Add this line
const showModal = ref(false);
const showModalCentro = ref(false);
const nombreCentro = ref("");
const calleCentro = ref("");
const selectedAction = ref({
  name: "",
  description: "",
  date_init: "",
  date_end: "",
  start_time: "",
  duration: "",
  price: "",
  capacity: "",
  languaje: "",
  age: "",
  category: "",
  center_id: null
});
const router = useRouter();

// Al montar el componente, llamamos a la API para obtener los centros

onMounted(() => {
  fetchCenters();
  fetchActions();
});

const openCentroModal = () => {
  showModalCentro.value = true;
  showModal.value = false;
};

const closeCentroModal = () => {
  showModalCentro.value = false;
};

const closeActionModal = () => {
  showModal.value = false;
};

const fetchCenters = async () => {
  try {
    const response = await axios.get(`${API_SERVER}/api/center/all`);
    centers.value = response.data;
  } catch (error) {
    console.error("Error al obtener los centros:", error);
  }
};

const insertarCentro = async () => {
  try {
    const response = await axios.post(`${API_SERVER}/api/center`, {
      name: nombreCentro.value,
      address: calleCentro.value,
    });

    if (response.status === 201) {  // Assuming 201 means created
      Swal.fire({
        icon: 'success',
        title: 'Centro Cívico creado!',
        showConfirmButton: false,
        timer: 1500
      });
      // Refresh centers list
      await fetchCenters();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error al crear el centro',
      });
    }
    showModalCentro.value = false;
  } catch (error) {
    console.error("Error al insertar el centro:", error);
    Swal.fire({
      icon: 'error',
      title: 'Error al crear el centro',
      text: error.message // Show error message
    });
  }
};

const insertAction = (action) => {
  showModalCentro.value = false;
  selectedAction.value = { ...action };
  showModal.value = true;
};
const fetchActions = async () => {
  try {
    const response = await axios.get(`${API_SERVER}/api/action/center`);
    actions.value = response.data.data;
  } catch (error) {
    console.error("Error al obtener las acciones:", error);
  }
};
const closeModal = () => {
  showModal.value = false;
  showModalCentro.value = false; // also close the other modal when closing action modal
};
const saveCenter = async () => {
  try {
    if (!nombreCentro.value.length || nombreCentro.value.length > 255) {
      Swal.fire({
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Cerrar",
        icon: "warning",
        title: "Introduce un nombre válido",
      });
      return;
    }
    if (!calleCentro.value.length || calleCentro.value.length > 255) {
      Swal.fire({
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Cerrar",
        icon: "warning",
        title: "Introduce una calle válida",
      });
      return;
    }

    // Here would go the logic to save the center
    console.log("Center saved successfully");

  } catch (error) {
    console.error("Error saving the center:", error);
    Swal.fire({
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Cerrar",
      icon: "error",
      title: "An error occurred",
      text: "Could not save the center. Please try again.",
    });
  }
};

const saveAction = async () => {
  let formattedStartTime = selectedAction.value.start_time;
  let formattedDateInit = selectedAction.value.date_init;
  let formattedDateEnd = selectedAction.value.date_end;

  if (formattedDateInit && formattedDateEnd && formattedDateInit > formattedDateEnd) {
    Swal.fire({
      confirmButtonColor: "#198754",
      confirmButtonText: "Cerrar",
      icon: "error",
      title: "Error on the dates",
      text: "The start date can't be after the end date.",
    });
    return;
  }

  if (formattedStartTime && formattedStartTime.length === 5) {
    const today = new Date().toISOString().split("T")[0];
    formattedStartTime = `${today} ${formattedStartTime}:00`;
  }

  if (formattedDateInit) {
    formattedDateInit = formattedDateInit.split("T")[0];
  }

  if (formattedDateEnd) {
    formattedDateEnd = formattedDateEnd.split("T")[0];
  }

  // Validation: If the user has not selected a category, show alert and stop the submission
  if (!selectedAction.value.category) {
    Swal.fire({
      confirmButtonColor: "#198754",
      confirmButtonText: "Cerrar",
      icon: "warning",
      title: "You must select a category before continuing",
    });
    return;
  }

  const dataToSend = {
    ...selectedAction.value,
    date_init: formattedDateInit,
    date_end: formattedDateEnd,
    start_time: formattedStartTime,
  };

  try {
    console.log("Data to send:", dataToSend);
    const response = await axios.post(`${API_SERVER}/api/action`, dataToSend, {
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
      },
    });

    console.log("Action created:", response.data);
    Swal.fire({
      confirmButtonColor: "#198754",
      confirmButtonText: "Cerrar",
      icon: "success",
      title: "Action created successfully",
    });

    selectedAction.value = {
      name: "",
      description: "",
      date_init: "",
      date_end: "",
      start_time: "",
      duration: "",
      price: "",
      capacity: "",
      languaje: "",
      age: "",
      category: "",
      center_id: null,
    };
    await fetchActions();
    closeActionModal(); // Close the Action Modal specifically
  } catch (error) {
    console.error("Error creating the action:", error.response?.data || error);
    Swal.fire({
      confirmButtonColor: "#198754",
      confirmButtonText: "Cerrar",
      icon: "error",
      title: "There was an error creating the action",
    });
  }
};

</script>

<style scoped>
.boton {
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  color: black;
}

.boton:hover {
  color: black;
}

.boton img {
  width: 16px;
  margin-right: 6px;
}
</style>