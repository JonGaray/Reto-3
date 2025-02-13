<script setup>
import { ref, defineEmits } from "vue";

const emit = defineEmits(["updateFilters"]);

const showFiltersModal = ref(false); // Controla la visibilidad del modal

const filters = ref({
  search: "",
  location: "",
  age: "",
  language: "",
  schedule: "",
  startDate: "",
  endDate: ""
});

// Función para reiniciar todos los filtros
const resetFilters = () => {
  filters.value = {
    search: "",
    location: "",
    age: "",
    language: "",
    schedule: "",
    startDate: "",
    endDate: ""
  };
  updateFilters(); // Emite los filtros reiniciados
};

// Función para emitir los filtros actualizados
const updateFilters = () => {
  emit("updateFilters", filters.value);
};

// Función para cerrar el modal
const closeModal = () => {
  showFiltersModal.value = false;
};

// Función para aplicar los filtros y cerrar el modal
const applyFilters = () => {
  updateFilters();
  closeModal();
};
</script>


<template>
  <h3 class="my-3">Búsqueda de actividades</h3>
  <div class="mb-3 d-flex justify-content-center">
    <input v-model="filters.search" @input="updateFilters" type="text" class="form-control" style="width: 70%;" placeholder="Buscar actividades...">
    <button class="btn btn-secondary ms-2" @click="showFiltersModal = true" style="width: 10%;">
      Filtros
    </button>
  </div>



  <div v-if="showFiltersModal" class="modal-overlay" @click="closeModal">
    <div class="modal-content p-4" @click.stop>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Ubicación</label>
          <input v-model="filters.location" @input="updateFilters" type="text" class="form-control" placeholder="Introduce una ubicación">
        </div>
        <div class="col-md-4">
          <label class="form-label">Rango de edad</label>
          <select v-model="filters.age" @change="updateFilters" class="form-select">
            <option value="">Selecciona rango de edad</option>
            <option value="6-12">6-12</option>
            <option value="12-17">12-17</option>
            <option value="18-25">18-25</option>
            <option value="26-35">26-35</option>
            <option value="36-50">36-50</option>
            <option value="50+">50+</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Idioma</label>
          <select v-model="filters.language" @change="updateFilters" class="form-select">
            <option value="">Selecciona idioma</option>
            <option value="Español">Español</option>
            <option value="Inglés">Inglés</option>
            <option value="Francés">Francés</option>
          </select>
        </div>
      </div>

      <div class="row g-3 mt-2">
        <div class="col-md-4">
          <label class="form-label">Horario</label>
          <select v-model="filters.schedule" @change="updateFilters" class="form-select">
            <option value="">Selecciona horario</option>
            <option value="morning">Mañana (08:00 - 12:00)</option>
            <option value="afternoon">Tarde (12:00 - 17:00)</option>
            <option value="night">Noche (17:00 - 22:00)</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Fecha inicio</label>
          <input v-model="filters.startDate" @input="updateFilters" type="date" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Fecha fin</label>
          <input v-model="filters.endDate" @input="updateFilters" type="date" class="form-control">
        </div>
      </div>

      <hr class="mt-4">

      <div class="d-flex justify-content-end">
        <button class="btn btn-secondary me-2" @click="closeModal">Cerrar</button>
        <button class="btn btn-success" @click="resetFilters">Reiniciar filtros</button>
      </div>
    </div>
  </div>

</template>

