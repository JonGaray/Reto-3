<script setup>
import { ref } from "vue";
import Encabezado from "../components/layout/Header.vue"
import Pie from '../components/layout/Footer.vue';
import FiltersUser from '../components/user/filters/filtersUser.vue'
import UserActivity from '../components/user/activities/userActivity.vue'

const filters = ref({});

const updateFilters = (newFilters) => {
  filters.value = newFilters;
};
</script>

<template>

  <body class="container text-center d-flex justify-content-center flex-column">

  <div class="row">
    <header class="col-12"> <Encabezado/> </header>
  </div>
  <div class="row mt-3">
    <div class="col-12"><FiltersUser @updateFilters="updateFilters" /></div>
  </div>
  <div class="row mt-3 d-flex justify-content-center">
    <div class="col-12 "><UserActivity :filters="filters"/></div>
  </div>
  <div class="row mt-3">
    <footer class="col-12"> <Pie/> </footer>
  </div>

  </body>

</template>

<style scoped>


</style>