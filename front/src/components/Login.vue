<script setup>
import { ref } from "vue";
import axios from "axios";
import Swal from 'sweetalert2';
import {useRouter} from "vue-router";

const mail = ref("");
const pass = ref("");
const users = ref([]);
const router = useRouter();
const API_SERVER = import.meta.env.VITE_API_SERVER; // Asegúrate de que la variable esté en .env
const userSession = ref("");
async function validarUsuario() {
  this.error = null;
  this.isLoading = true;

  try {
    const response = await axios.post(`${API_SERVER}/api/login`, {
      email: mail.value,
      password: pass.value,
    });

    sessionStorage.setItem("user", JSON.stringify(response.data));
    userSession.value = sessionStorage.getItem("user");

    if (userSession.value) {
      router.push({ path: "/" });
    }

  } catch (error) {
    // ⚠️ Evita que Axios muestre el error en la consola
    if (error.response) {
      if (error.response.status === 400) {
        Swal.fire({
          icon: "error",
          title: "Credenciales incorrectas",
          text: "Verifica tu correo y contraseña.",
        });
      } else if (error.response.status === 422) {
        Swal.fire({
          icon: "error",
          title: "Error de validación",
          text: "Verifica los datos ingresados.",
        });
      } else if (error.response.status === 500) {
        Swal.fire({
          icon: "error",
          title: "Error del servidor",
          text: "Inténtalo más tarde.",
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error desconocido",
          text: `Código: ${error.response.status}`,
        });
      }
    } else {
      // Si no hay respuesta del servidor (error de conexión, por ejemplo)
      Swal.fire({
        icon: "error",
        title: "Error de conexión",
        text: "No se pudo conectar con el servidor.",
      });
    }
  } finally {
    this.isLoading = false;
  }
}
function register(){
  router.push({path: "/register"});
}
</script>

<template>
  <div class="container text-center d-flex flex-column align-items-center justify-content-center vh-100">
    <div class="card shadow-lg p-4 w-50">
      <div class="card-body">
        <h3 class="card-title mb-3">Iniciar sesión</h3>

        <!-- FORMULARIO PARA MANEJAR ENTER -->
        <form @submit.prevent="validarUsuario()">
          <div class="mb-3">
            <div class="form-floating w-75 mx-auto">
              <input
                  type="email"
                  id="email"
                  v-model="mail"
                  class="form-control"
                  placeholder=" "
                  required
              />
              <label for="email">Correo electrónico</label>
            </div>
          </div>

          <div class="mb-3">
            <div class="form-floating w-75 mx-auto">
              <input
                  type="password"
                  id="pass"
                  v-model="pass"
                  class="form-control"
                  placeholder=" "
                  required
              />
              <label for="pass">Contraseña</label>
            </div>
          </div>

          <!-- BOTÓN AHORA ES TYPE SUBMIT -->
          <button type="submit" class="btn btn-success w-75 mx-auto my-2">
            Iniciar sesión
          </button>
        </form>

        <p class="mt-3 text-muted">
          <a href="" @click.prevent="register()">Registrate</a> y explora nuestras actividades deportivas y culturales
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.form-floating {
  position: relative;
}

.form-floating input {
  height: 3rem;
  padding: 1rem 0.75rem;
  font-size: 1rem;
}

.form-floating label {
  position: absolute;
  top: 30%; /* Centrado verticalmente */
  left: 0.75rem;
  transform: translateY(-30%);
  font-size: 1rem;
  color: #6c757d;
  transition: all 0.2s ease-in-out;
  background-color: white; /* Fondo transparente antes de hacer clic */
  padding: 0;
  z-index: 2;
  height: auto;
  pointer-events: none;
}

.form-floating input:focus ~ label,
.form-floating input:not(:placeholder-shown) ~ label {
  top: -0.3rem;
  font-size: 1rem;
  color: black;
  background-color: white;
  z-index: 1000;
}

</style>
