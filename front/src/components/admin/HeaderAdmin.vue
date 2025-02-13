<template>
  <div class="container mb-3">
    <div class="row">
      <div class="col d-flex justify-content-between">
        <h4>Panel de Administrador</h4>
        <button class="btn btn-sm btn-outline-success insert" @click="insertAction(action)">
          <img src="../../assets/img/anadir.png" alt="insert">
          Nueva Actividad</button>
      </div>
    </div>

    <div v-if="showModal" class="modal-overlay col-10 offset-1 " @click="closeModal">
      <div class="modal-content" @click.stop>
        <h4>Editar Acción</h4>
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
            <label for="duration"><b>Duración</b></label>
            <input v-model="selectedAction.duration" id="duration" type="time" class="form-control" required />
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
            <label for="language"><b>Idioma</b></label>
            <input v-model="selectedAction.language" id="language" type="text" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="age"><b>Edad</b></label>
            <input v-model="selectedAction.age" id="age" type="number" class="form-control" required />
          </div>
          <div class="form-group col-8 offset-2">
            <label for="category"><b>Categoria</b></label>
            <select v-model="selectedAction.category" id="category" class="form-select" required>
              <option value="cultura">Cultura</option>
              <option value="deportes">Deportes</option>
              <option value="educacion">Educación</option>
              <option value="medio ambiente">Medio Ambiente</option>
            </select>
          </div>
          <div class="form-group col-8 offset-2">
            <label for="center"><b>Centro Cívico</b></label>
            <select id="center" class="form-select" required v-model="selectedAction.center">
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
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const centers = ref([]);
const showModal = ref(false);
const selectedAction = ref({
  name: "",
  description: "",
  date_init: "",
  date_end: "",
  duration: "",
  price: "",
  capacity: "",
  language: "",
  age: "",
  category: "",
  center: null  // Aquí el valor de center es null por defecto
});

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

const insertAction = (action) => {
  selectedAction.value = { ...action };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveAction = () => {
  // Lógica para guardar la acción
  console.log("Acción guardada", selectedAction.value);
  closeModal();
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
