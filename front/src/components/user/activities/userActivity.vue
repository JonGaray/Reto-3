<script setup>
import { ref, computed, watch, onMounted, defineProps } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const props = defineProps({
  filters: Object
});
const API_SERVER = import.meta.env.VITE_API_SERVER;

const actions = ref([]);
const enrolledActivities = ref({});
const router = useRouter();
const user = ref(null);

onMounted(() => {
  const storedUser = sessionStorage.getItem("user");
  if (storedUser) {
    try {
      user.value = JSON.parse(storedUser);
    } catch (error) {
      console.error("Error al parsear el usuario:", error);
    }
  }
});

const formatTime = (time) => {
  // Si la hora está en formato 'HH:MM:SS', recortamos los últimos 3 caracteres ('SS')
  return time ? time.slice(0, 5) : ''; 
};

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

const fetchActions = async () => {
  try {
    const response = await axios.get(`${API_SERVER}/api/action/all`);
    actions.value = Array.isArray(response.data.data) ? response.data.data : Object.values(response.data.data);
  } catch (error) {
    console.error("Error al cargar actividades:", error);
    actions.value = [];
  }
};

const filteredActions = computed(() => {
  return actions.value.filter(activity => {
    const { search, location, age, language, schedule, startDate, endDate } = props.filters;
    
    if (search && !activity.name.toLowerCase().includes(search.toLowerCase())) return false;
    if (location && !activity.center.name.toLowerCase().includes(location.toLowerCase())) return false;
    if (age && activity.age) {
      const [minAge, maxAge] = age.split("-");
      if (age === "50+" && parseInt(activity.age) < 50) return false;
      if (parseInt(activity.age) < parseInt(minAge) || (maxAge && parseInt(activity.age) > parseInt(maxAge))) return false;
    }
    if (language && activity.languaje.toLowerCase() !== language.toLowerCase()) return false;
    if (activity.start_time) {
      const hour = parseInt(activity.start_time.split(":")[0]);
      if (schedule === "morning" && (hour < 8 || hour >= 12)) return false;
      if (schedule === "afternoon" && (hour < 12 || hour >= 17)) return false;
      if (schedule === "night" && (hour < 17 || hour >= 22)) return false;
    }
    if (startDate && activity.date_init < startDate) return false;
    if (endDate && activity.date_end > endDate) return false;

    return true;
  });
});

const checkUserEnrollments = async () => {
  if (!user.value) return;
  const enrollments = {};

  for (const activity of actions.value) {
    try {
      const response = await axios.get(`${API_SERVER}/api/user/joined`, {
        params: {
          user_id: user.value.id,
          action_id: activity.id,
        },
      });
      enrollments[activity.id] = response.data.enrolled;
    } catch (error) {
      console.error(`Error verificando inscripción en actividad ${activity.id}:`, error);
      enrollments[activity.id] = false;
    }
  }
  enrolledActivities.value = enrollments;
};

const toggleInscription = async (activity) => {
  if (!user.value) {
    router.push("/login");
    return;
  }
  
  try {
    if (enrolledActivities.value[activity.id]) {
      await axios.post(`${API_SERVER}/api/user/joinDelete`, {
        user_id: user.value.id,
        action_id: activity.id,
      });
      await axios.post(`${API_SERVER}/api/action/aumentarPlazas`, {
        action_id: activity.id,
      });
    } else{
      const response = await axios.post(`${API_SERVER}/api/user/join`, {
        user_id: user.value.id,
        action_id: activity.id,
      });
      
      const responsePlazas = await axios.post(`${API_SERVER}/api/action/reducirPlazas`, {
        action_id: activity.id,
      });
      
    }
    await fetchActions();  // Recarga todas las acciones
    await checkUserEnrollments();  // Recarga el estado de las inscripciones
  } catch (error) {
    console.error("Error al procesar inscripción:", error);
    alert("Hubo un problema con la inscripción.");
  }
};

watch(actions, checkUserEnrollments, { deep: true });
onMounted(() => {
  fetchActions();
  checkUserEnrollments();
});
</script>

<template>
  <div class="row">
    <div v-if="filteredActions && filteredActions.length === 0" class="col-12 text-center">
      <p class="alert alert-success">No hay actividades disponibles</p>
    </div>
    <div v-for="activity in filteredActions" :key="activity.id" class="col-md-4">
      <div class="card mt-3" style="height: 520px; max-height: 520px;">
        <img v-if="activity.category === 'cultura'" src="../../../assets/img/cultura.jpg" class="card-img-top" alt="Cultura" style="height: 200px; object-fit: cover;">
        <img v-else-if="activity.category === 'deportes'" src="../../../assets/img/deportes.jpg" class="card-img-top" alt="Deportes" style="height: 200px; object-fit: cover;">
        <img v-else-if="activity.category === 'educacion'" src="../../../assets/img/educacion.jpg" class="card-img-top" alt="Educación" style="height: 200px; object-fit: cover;">
        <img v-else-if="activity.category === 'medio ambiente'" src="../../../assets/img/medio%20ambiente.jpg" class="card-img-top" alt="Educación" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex justify-content-between flex-column text-start" >
          <div class="d-flex justify-content-between">
        <span class="badge bg-light text-dark">
          {{ activity.capacity }} plazas disponibles
        </span>
            <span class="badge bg-light text-dark">{{ activity.price }}€/hora</span>
          </div>
          <h4 class="mt-3">{{ activity.name }}</h4>
          <p class="text-secondary mb-0"> Centro: {{ activity.center ? activity.center.name : 'Centro no disponible' }}</p>
          <p class="text-muted mb-0">Edad: {{ activity.age }}</p>
          <p class="text-muted mb-0">Idioma: {{ activity.languaje }}</p>
          <p class="text-muted mb-0">Horario: {{ formatTime(activity.start_time) }} - {{ formatTime(calculateEndTime(activity.start_time, activity.duration)) }}</p>
          <p class="text-muted">Del {{ activity.date_init }} al {{ activity.date_end }}</p>

          <button
              v-if="user"
              class="btn w-100"
              :class="{
          'btn-secondary': activity.capacity === 0 || !enrolledActivities[activity.id],
          'btn-danger': enrolledActivities[activity.id],
          'btn-success': activity.capacity > 0 && !enrolledActivities[activity.id]
        }"
              :disabled="activity.capacity === 0 && !enrolledActivities[activity.id]"
              @click="toggleInscription(activity)"
          >
            {{ enrolledActivities[activity.id] ? "Desapuntarse" : activity.capacity === 0 ? "Plazas completas" : "Inscribirse" }}
          </button>
          <button
              v-else
              @click="router.push('/login')"
              class="btn w-100"
              :class="{'btn-secondary': activity.capacity === 0, 'btn-success': activity.capacity > 0}"
              :disabled="activity.capacity === 0"
          >
            {{ activity.capacity === 0 ? "Plazas completas" : "Inscribirse" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>