<template>
  <aside class="sidebar bg-dark text-white vh-100 d-flex flex-column p-3">
    <!-- Brand -->
    <div class="d-flex align-items-center mb-2">
      <i class="bi bi-speedometer2 fs-4 me-2"></i>
      <span class="fs-5 fw-bold">Admin Panel</span>
    </div>

    <hr>

    <!-- Nav Menu -->
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <RouterLink to="/Dashboard" class="nav-link d-flex align-items-center text-white" active-class="active">
          <i class="fas fa fa-home me-2"></i> Dashboard
        </RouterLink>
      </li>

      <li>
        <RouterLink to="/ListTeamRegister" class="nav-link d-flex align-items-center text-white" active-class="active">
          <i class="fas fa fa-users me-2"></i> List Team Registration
        </RouterLink>
      </li>


       <li>
        <RouterLink to="/ListTeamRegistration" class="nav-link d-flex align-items-center text-white" active-class="active">
          <i class="fas fa fa-users me-2"></i> List Team Registration New
        </RouterLink>
      </li>


       <li>
        <button class="dropdown-item text-danger" @click="logout">
            <i class="fas fa fa-upload me-2"></i> Logout
          </button>
      </li>
   
    </ul>

  </aside>
</template>





<script setup>
import { RouterLink, useRouter } from 'vue-router'
import { ref } from 'vue'
import axios from 'axios'

const router = useRouter()

const token = localStorage.getItem('token')

const user = ref({
  name: 'Apregi P',
  avatar: 'https://ui-avatars.com/api/?name=Apregi+P&background=0D6EFD&color=fff'
})

const logout = async () => {
  if (!token) {
    alert('Kamu belum login!')
    return
  }

  try {
    // sertakan token di header
    const res = await axios.post(
      '/api/logout',
      {}, 
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )

    if (res.data.success) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      router.push('/')
    } else {
      alert(res.data.message)
    }
  } catch (err) {
    console.error(err.response?.data || err)
    alert('Logout gagal. Cek token atau koneksi API.')
  }
}
</script>






<style scoped>
.sidebar {
  width: 240px;
  transition: all 0.3s;
}

.sidebar .nav-link {
  border-radius: 8px;
  margin-bottom: 5px;
  padding: 10px 12px;
  transition: background 0.3s, color 0.3s;
}

.sidebar .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.sidebar .nav-link.active {
  background-color: #0d6efd;
  color: #fff !important;
}
</style>
