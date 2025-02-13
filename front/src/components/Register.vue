<template>
  <div class="container text-center d-flex flex-column align-items-center justify-content-center vh-100">
    <div class="card shadow-lg p-4 w-50">
      <div class="card-body">
        <h3 class="card-title mb-3">Registro</h3>

        <div class="mb-3">
          <div class="form-floating w-75 mx-auto">
            <input
                type="text"
                id="nombre"
                v-model="nombre"
                class="form-control"
                placeholder=" "
            />
            <label for="nombre">Nombre</label>
          </div>
        </div>

        <div class="mb-3">
          <div class="form-floating w-75 mx-auto">
            <input
                type="text"
                id="apellido"
                v-model="apellido"
                class="form-control"
                placeholder=" "
            />
            <label for="apellido">Apellido</label>
          </div>
        </div>

        <div class="mb-3">
          <div class="form-floating w-75 mx-auto">
            <input
                type="email"
                id="email"
                v-model="email"
                class="form-control"
                placeholder=" "
            />
            <label for="email">Correo electrónico</label>
          </div>
        </div>

        <div class="mb-3">
          <div class="form-floating w-75 mx-auto">
            <input
                type="password"
                id="password"
                v-model="password"
                class="form-control"
                placeholder=" "
            />
            <label for="password">Contraseña</label>
          </div>
        </div>

        <div class="mb-3">
          <div class="form-floating w-75 mx-auto">
            <input
                type="text"
                id="dni"
                v-model="dni"
                class="form-control"
                placeholder=" "
            />
            <label for="dni">DNI</label>
          </div>
        </div>

        <button class="btn btn-success w-75 mx-auto my-2" @click="registrarUsuario()">Registrarse</button>

        <p class="mt-3 text-muted">
          ¿Ya tienes una cuenta? <a href="" @click="login()">Inicia sesión</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import {useRouter} from "vue-router";
import Swal from 'sweetalert2';
import axios from "axios";


const router = useRouter();
const API_SERVER = import.meta.env.VITE_API_SERVER; // Asegúrate de que la variable esté en .env
const nombre = ref('')
const apellido = ref('')
const email = ref('')
const password = ref('')
const dni = ref('')


async function registrarUsuario() {
  this.error = null;
  this.isLoading = true;
  try {
    if (nombre.value.length > 255) {
      Swal.fire({
        icon: 'error',
        title: 'Exceso de caracteres',
        text: 'El nombre no puede superar los 255 caracteres',
      });
      return;
    }

    if (apellido.value.length > 255) {
      Swal.fire({
        icon: 'error',
        title: 'Exceso de caracteres',
        text: 'El apellido no puede superar los 255 caracteres',
      });
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
      Swal.fire({
        icon: 'error',
        title: 'Email inválido',
        text: 'Por favor, ingrese un email válido',
      });
      return;
    }

    const dniRegex = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i;
    if (!dniRegex.test(dni.value)) {
      Swal.fire({
        icon: 'error',
        title: 'DNI inválido',
        text: 'Por favor, ingrese un DNI válido (8 números seguidos de una letra)',
      });
      return;
    }

    if (password.value.length < 8) {
      Swal.fire({
        icon: 'error',
        title: 'Contraseña débil',
        text: 'La contraseña debe tener al menos 8 caracteres',
      });
      return;
    }

    const response = await axios.post(`${API_SERVER}/api/user`, {
      name: nombre.value,
      lastname: apellido.value,
      email: email.value,
      dni: dni.value,
      password: password.value,
    });

    if (response.status == 200) {
      router.push({path: "/" });
    }

  } catch (error) {
    console.log(error); // Cambiado de console.log(response) a console.log(error)
  } finally {
    this.isLoading = false;
  }
}

function login(){
  router.push({path: "/login"});
}
</script>

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
  top: 30%;
  left: 0.75rem;
  transform: translateY(-30%);
  font-size: 1rem;
  color: #6c757d;
  transition: all 0.2s ease-in-out;
  background-color: white;
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