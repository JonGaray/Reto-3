<script setup>
import {ref, onMounted, watch} from "vue";
import Footer from "@/components/layout/Footer.vue";
import {useRouter} from "vue-router";
import axios from "axios";

const API_SERVER = import.meta.env.VITE_API_SERVER;
const actions = ref([]);
const router = useRouter();
const user = ref({});
const mail = ref("");
const pass = ref("********")
const isEditing = ref(false);
const cambioEditar = ref(false);
console.log(actions)


onMounted(() => { //funcion que realizaremos al montar el componente
  const storedUser = sessionStorage.getItem("user"); //cargar en el SS los datos del usuario
  if (storedUser) {
    try {
      user.value = JSON.parse(storedUser);
      mail.value = user.value.email || "";
    } catch (error) {
      console.error("Error al parsear el usuario:", error);
    }
  }
});

const fetchActions = async () => { //Funcion para recoger las actividades
  if (!user.value.id) return;

  try {
    const response = await axios.get(`${API_SERVER}/api/user/${user.value.id}/actions`);
    actions.value = response.data.data;
    console.log(response.data.data)
  } catch (error) {
    console.error("Error al obtener las acciones:", error);
  }
};

function editarPerfil() { //Funcion para habilitar la edicion del perfil
  isEditing.value = true;
  cambioEditar.value = true;
}

async function añadirCambios() { //funcion para guardar los cambios realizados 
  try {
    const response = await axios.post(`${API_SERVER}/api/user/${user.value.id}/update`, {
      name: user.value.name,
      lastname: user.value.lastname,
      email: mail.value,
      password: pass.value,
    });

    if (response.status === 200) {
      isEditing.value = false;
      sessionStorage.setItem("user", JSON.stringify(user.value));
    }
    cambioEditar.value=false;
  } catch (error) {
    console.error("Error al guardar los cambios:", error);
    alert("Error al guardar los cambios. Por favor, inténtalo de nuevo.");
  }
}


watch(user, (newUser) => {
  if (newUser.email) {
    mail.value = newUser.email;
  }
});


watch(() => user.value.id, (id) => {
  if (id) {
    fetchActions();
  }
});
async function deinscribirte(id){
  const response =  await axios.post(`${API_SERVER}/api/user/joinDelete`, {
    user_id: user.value.id,
    action_id: id,
  });
  console.log(id)
  await axios.post(`${API_SERVER}/api/action/aumentarPlazas`, {
    action_id: id,
  });
fetchActions(); //llamada para recargar las actividades
}
const calculateEndTime = (startTime, durationInMinutes) => {
  const [hours, minutes] = startTime.slice(0, 5).split(":").map(Number); // Recortamos la cadena de inicio
  const startDate = new Date();
  startDate.setHours(hours, minutes, 0); // Establecer la hora de inicio

  startDate.setMinutes(startDate.getMinutes() + durationInMinutes); // Sumar la duración

  const endHour = startDate.getHours();
  const endMinutes = startDate.getMinutes();

  // Formatear la hora de finalización
  const formattedEndTime = `${endHour.toString().padStart(2, "0")}:${endMinutes.toString().padStart(2, "0")}`;
  return formattedEndTime;
};
const formatTime = (time) => {
  // Si la hora está en formato 'HH:MM:SS', recortamos los últimos 3 caracteres ('SS')
  return time ? time.slice(0, 5) : '';
};

onMounted(() => {
  fetchActions();
})
</script>

<template>
  <div class="container">

    <div class="row justify-content-center mt-3">
      <div class="col-12 d-flex justify-content-between align-items-center mt-2 mb-5">
        <div class="text-center flex-grow-1">
          <h1>Actividades disponibles</h1>
          <p>Explora y regístrate en nuestras actividades deportivas y culturales</p>
        </div>
        <button class="btn btn-outline-secondary" @click="router.push('/')">Volver</button>
      </div>
    </div>


    <div class="card shadow-sm mb-5 position-relative" style="max-width: 1000px; margin: 0 auto;">  <!-- Added max-width and margin -->
      <div class="card-body d-flex justify-content-evenly">

        <div class="position-absolute top-0 end-0 m-3 ms-5" v-if="!cambioEditar">
          <button class="btn btn-success" @click="editarPerfil()">Editar</button>
        </div>

        <div class="d-flex align-items-center mb-4">
          <div class="bg-secondary-subtle p-3 rounded-circle me-3">
            <i class="bi bi-person fs-4"></i>
          </div>
          <img src="../../assets/img/fotoPerfil.png" alt="Foto de perfil" style="width: 50%">
        </div>

        <div class="mb-4 d-flex flex-column align-items-end">
          <div class="mb-3 d-flex">
            <label class="text-muted mb-1 me-4">Nombre:</label>
            <input style="min-width: 150px; max-width: 250px" type="text" v-model="user.name" class="form-control" :disabled="!isEditing" />
          </div>

          <div class="mb-3 d-flex">
            <label class="text-muted mb-1 me-4">Apellido:</label>
            <input style="min-width: 150px; max-width: 250px" type="text" v-model="user.lastname" class="form-control" :disabled="!isEditing" />
          </div>

          <div class="mb-3 d-flex">
            <label class="text-muted mb-1 me-4">Email:</label>
            <input style="min-width: 150px; max-width: 250px" type="text" v-model="mail" class="form-control" :disabled="!isEditing" />
          </div>

          <div class="mb-3 d-flex">
            <label class="text-muted mb-1 me-4">DNI:</label>
            <input style="min-width: 150px; max-width: 250px" type="text" v-model="user.dni" class="form-control" :disabled="!isEditing" />
          </div>

          <div class="mb-3 d-flex">
            <label class="text-muted mb-1 me-4">Contraseña:</label>
            <input style="min-width: 150px; max-width: 250px;" type="password" v-model="pass" class="form-control" :disabled="!isEditing" />
          </div>

          <div class="mt-3 text-center" v-if="isEditing">
            <button class="btn btn-success  " @click="añadirCambios()">Guardar Cambios</button>
          </div>
        </div>
      </div>
    </div>

    <h3 class="h5 text-center mb-4">Actividades Inscritas</h3>
    <div class="row justify-content-center mb-5">
      <div v-for="action in actions" :key="action.id" class="card p-3 shadow col-md-5 col-sm-12 my-3 mx-2">
        <img v-if="action.category === 'cultura'" src="../../assets/img/cultura.jpg" class="card-img-top" alt="Cultura" style="height: 200px; object-fit: cover;">
        <img v-else-if="action.category === 'deportes'" src="../../assets/img/deportes.jpg" class="card-img-top" alt="Deportes" style="height: 200px; object-fit: cover;">
        <img v-else-if="action.category === 'educacion'" src="../../assets/img/educacion.jpg" class="card-img-top" alt="Educación" style="height: 200px; object-fit: cover;">
        <img v-else-if="action.category === 'medio ambiente'" src="../../assets/img/medio%20ambiente.jpg" class="card-img-top" alt="Educación" style="height: 200px; object-fit: cover;">

        <div class="d-flex justify-content-between">
          <span class="badge bg-light text-dark">{{ action.capacity }} plazas disponibles</span>
          <span class="badge bg-light text-dark">{{ action.price }}€/mes</span>
        </div>
        <h4 class="mt-3">{{ action.name }}</h4>
        <p class="text-secondary mb-1">{{ action.center?.name }}</p>
        <p class="text-muted mb-0">Horario: {{ formatTime(action.start_time) }} - {{ formatTime(calculateEndTime(action.start_time, action.duration)) }}</p>
        <button class="btn btn-danger w-100 mt-3" @click="deinscribirte(action.id)">Desinscribirse</button>
      </div>
    </div>

    <div class="d-flex justify-content-center my-5">
      <Footer/>
    </div>
  </div>
</template>
