<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
    <!-- Hamburger (mobile only) -->
    <button
      class="navbar-toggler d-lg-none border-0"
      type="button"
      data-bs-toggle="offcanvas"
      data-bs-target="#sidebarOffcanvas"
      aria-controls="sidebarOffcanvas"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Brand (desktop only) -->
    <a class="navbar-brand fw-bold ms-2 d-none d-lg-block" href="#">
      TES WEB DEVELOPER - APREGI PRATAYUDA
    </a>

    <!-- User Dropdown -->
    <div class="dropdown ms-auto">
      <a
        href="#"
        class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
        id="userDropdown"
        data-bs-toggle="dropdown"
      >
        <img
          :src="user.avatar"
          alt="avatar"
          width="32"
          height="32"
          class="rounded-circle me-2"
        />
        <strong>{{ user.name }}</strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-end shadow">
        <!-- <li>
          <RouterLink class="dropdown-item" to="/profile">
            <i class="bi bi-person-circle me-2"></i> Profile
          </RouterLink>
        </li> -->
        <!-- <li><hr class="dropdown-divider"></li> -->
        <li>
          <button class="dropdown-item text-danger" @click="logout">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </button>
        </li>
      </ul>
    </div>
  </nav>
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
.navbar-toggler {
  outline: none !important;
  box-shadow: none !important;
}
</style>
