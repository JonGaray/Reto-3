<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const user = ref(null);

onMounted(() => {
  const storedUser = sessionStorage.getItem("user");
  if (storedUser) {
    try {
      user.value = JSON.parse(storedUser); // Convertimos el string a objeto
    } catch (error) {
      console.error("Error al parsear el usuario:", error);
    }
  }
});

function iniciarSesion() {
  router.push({ path: "/login" });
}

function cerrarSesion() {
  sessionStorage.clear();
  location.reload();
}
function perfilUser(){
  router.push({path: "/perfil"})
}
function panelAdmin(){
  router.push({path: "/panel"})
}
</script>

<template>
  <div class="row justify-content-center mt-3">
    <div class="col-12 d-flex justify-content-between align-items-center mt-2 mb-5">
      <div class="flex-grow-1 d-flex flex-column align-items-start">
        <h1>Actividades disponibles</h1>
        <p>Explora y regístrate en nuestras actividades deportivas y culturales</p>
      </div>
      <div v-if="!user">
        <button class="btn btn-success" @click="iniciarSesion()">Iniciar sesión</button>
      </div>
      <div v-if="user" class="d-flex align-items-center gap-2">
        <button class="btn btn-success" @click="cerrarSesion()">Cerrar sesión</button>
        <div v-if="user.role === 'user'">
          <a class="btn btn-outline-success" @click="perfilUser()">Ver perfil</a>
        </div>
        <div v-if="user.role === 'admin'">
          <a class="btn btn-outline-success" @click="panelAdmin()">Panel de administracion</a>

        </div>
      </div>
    </div>
  </div>
</template>



<style scoped>

</style>