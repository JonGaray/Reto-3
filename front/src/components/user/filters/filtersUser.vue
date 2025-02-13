<script setup>
import { ref, defineEmits } from "vue";

const emit = defineEmits(["updateFilters"]);

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

const updateFilters = () => {
  emit("updateFilters", filters.value);
};
</script>

<template>
  <h3>Búsqueda de actividades</h3>
  <div class="mb-3 d-flex">
    <input v-model="filters.search" @input="updateFilters" type="text" class="form-control me-2" placeholder="Buscar actividades...">
    <button class="btn btn-light border d-flex align-items-center gap-2" @click="resetFilters">
      <img src="../../../assets/img/filtrar.png" alt="Reiniciar" width="20" height="20"> Reiniciar
    </button>
  </div>

  <h4>Filtros</h4>
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
        <option value="Frances">Francés</option>
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
</template>
