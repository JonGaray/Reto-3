<template>
  <div class="container mb-3">
    <div class="row">
      <div class="col d-flex justify-content-between">
        <div class="align-items-start">
          <h4>Panel de Administrador</h4>
        </div>
        <div class="d-flex flex-row">
          <button class="btn btn-sm btn-outline-success insert me-4   " @click="insertAction(action)">
            <img src="../../assets/img/anadir.png" alt="insert">
            Nueva Actividad</button>
          <button class="btn btn-sm btn-outline-success insert me-4   " @click="insertCentro()">
            <img src="../../assets/img/anadir.png" alt="insert">
            Nuevo centro</button>
          <button class="btn btn-outline-success" @click="router.push({path:'/'})">Volver</button>

        </div>
      </div>
    </div>

    <div v-if="showModal" class="modal-overlay col-10 offset-1 my-3" @click="closeModal">
      <div class="modal-content" @click.stop>
        <h4>Insertar Acción</h4>
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
            <button class="btn btn-danger mt-3" @click="closeModal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>



    <div v-if="showModalCentro" class="modal-overlay col-10 offset-1 my-3" @click="closeModal">
      <div class="modal-content" @click.stop>
        <h4>Crear centro</h4>
        <form @submit.prevent="saveCenter">
          <div class="form-group col-8 offset-2">
            <label for="name"><b>Nombre</b></label>
            <input v-model="nombreCentro" id="name" type="text" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="description"><b>Calle</b></label>
            <textarea v-model="calleCentro" id="calle" class="form-control" required />
          </div>
          <div class="d-flex justify-content-center">
            <input type="submit" class="btn btn-success my-3" @click="insertarCentro()">
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
import {useRouter} from "vue-router";

const API_SERVER = import.meta.env.VITE_API_SERVER;
const centers = ref([]);
const showModal = ref(false);
const showModalCentro = ref(false);
const nombreCentro=ref("");
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
});

const fetchCenters = async () => {
  try {
    const response = await axios.get(`${API_SERVER}/api/center/all`);
    centers.value = response.data;
  } catch (error) {
    console.error("Error al obtener los centros:", error);
  }
};

const insertCentro = () => {
  showModalCentro.value = true;
  showModal.value=false;
}
async function insertarCentro(){
  const response = await axios.post(`${API_SERVER}/api/center`, {
    name: nombreCentro.value,
    address: calleCentro.value,
  });
  showModalCentro.value=false;
}
const insertAction = (action) => {
  showModalCentro.value = false;
  selectedAction.value = { ...action };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
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

    // Aquí iría la lógica para guardar el centro
    console.log("Centro guardado correctamente");

  } catch (error) {
    console.error("Error al guardar el centro:", error);
    Swal.fire({
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Cerrar",
      icon: "error",
      title: "Ocurrió un error",
      text: "No se pudo guardar el centro. Inténtalo de nuevo.",
    });
  }
};

const saveAction = async () => {
  let formattedStartTime = selectedAction.value.start_time;
  let formattedDateInit = selectedAction.value.date_init;
  let formattedDateEnd = selectedAction.value.date_end;

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

  // 🔹 Validación: Si el usuario no ha seleccionado una categoría, mostramos alerta y detenemos el envío
  if (!selectedAction.value.category) {
    Swal.fire({
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Cerrar",
      icon: "warning",
      title: "Debes seleccionar una categoría antes de continuar",
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
    console.log("Datos a enviar:", dataToSend);
    const response = await axios.post(`${API_SERVER}/api/action`, dataToSend, {
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
      },
    });

    console.log("Acción creada:", response.data);
    Swal.fire({
      confirmButtonColor: "#198754",
      confirmButtonText: "Cerrar",
      icon: "success",
      title: "Acción creada con éxito",
    });

    // 🔹 Reiniciamos el formulario
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
      category: "", // 💡 Reiniciamos category
      center_id: null,
    };

    fetchActions();
    closeModal();
  } catch (error) {
    console.error("Error al crear la acción:", error.response?.data || error);
    Swal.fire({
      confirmButtonColor: "#198754",
      confirmButtonText: "Cerrar",
      icon: "error",
      title: "Hubo un error al crear la acción",
    });
  }
};


</script>

<style scoped>
.insert {
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  color: black;
}

.insert:hover {
  color: black;
}

.insert img {
  width: 16px;
  margin-right: 6px;
}
</style>