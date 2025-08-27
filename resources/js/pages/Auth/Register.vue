<!-- <script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2' 

const fullname = ref('')
const username = ref('')
const email = ref('')
const phone_number = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const register = async () => {
  // validasi dari frontend
  let errors = []
  // Name
if (!fullname.value) {
  errors.push("Nama lengkap wajib diisi.")
} else if (typeof fullname.value !== "string") {
  errors.push("Nama lengkap harus berupa teks.")
} else if (!/^[a-zA-Z\s]+$/.test(fullname.value)) {
  errors.push("Nama lengkap hanya boleh berisi huruf dan spasi.")
} else if (fullname.value.trim().length < 3) {
  errors.push("Nama lengkap harus lebih dari 3 karakter.")
}


 // Username (alphanumeric, min 6 karakter)
  if (!username.value) {
    errors.push("Username wajib diisi.")
  } else if (!/^[a-zA-Z0-9]+$/.test(username.value)) {
    errors.push("Username hanya boleh huruf dan angka (tanpa spasi/simbol).")
  } else if (username.value.length < 6) {
    errors.push("Username harus lebih dari 6 karakter.")
  }

  // Email
  if (!email.value) {
    errors.push("Email wajib diisi.")
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    errors.push("Format email tidak valid.")
  }

  // Password
  if (!password.value) {
    errors.push("Password wajib diisi.")
  } else if (password.value.length < 8 || password.value.length > 16) {
    errors.push("Password harus 8–16 karakter.")
  }

  // Phone number
  if (!phone_number.value) {
    errors.push("Nomor telepon wajib diisi.")
  } else if (!/^[0-9]+$/.test(phone_number.value)) {
    errors.push("Nomor telepon hanya boleh angka.")
  }

  if (errors.length > 0) {
    Swal.fire({
      icon: 'error',
      title: 'Validasi Gagal!',
      html: errors.join('<br>')
    })
    return
  }
// end validasi dari frontend
  
  try {
    const res = await axios.post('/api/register-akun', {
      fullname: fullname.value,
      username: username.value,
      email: email.value,
      phone_number: phone_number.value,
      password: password.value
    })

    Swal.fire({
      icon: 'success',
      title: 'Registrasi Berhasil!',
      text: res.data.message,
      showConfirmButton: false,
      timer: 3000
    })

    setTimeout(() => {
      router.push('/')
    }, 3000)

  } catch (err) {
    if (err.response && err.response.data) {
      const data = err.response.data
      if (data.errors) {
        const errorMessages = Object.values(data.errors).flat().join('<br>')
        Swal.fire({
          icon: 'error',
          title: data.message || 'Registrasi gagal!',
          html: errorMessages
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Registrasi gagal!',
          text: data.message || 'Terjadi kesalahan!'
        })
      }
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Server Error!',
        text: 'Terjadi kesalahan server!'
      })
    }
  }
}

</script>


<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-5 rounded-4" style="width: 450px; min-height: 600px;">
      <h2 class="mb-2 text-center fw-bold">Buat Akun Baru</h2>
      <p class="text-center text-muted mb-3">Daftar Sekarang Untuk Bergabung Dengan Turnament Kami</p>

    <div v-if="error" class="alert alert-danger py-2">
      <i class="fas fa-exclamation-triangle me-2"></i><small>Warning</small>
    <ul class="mb-0">
      <li v-for="(msg, idx) in error" :key="idx">{{ msg }}</li>
    </ul>
  </div>


      <form @submit.prevent="register">
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input v-model="fullname" type="text" class="form-control form-control-lg" placeholder="Example: marks john"  />
        </div>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input v-model="username" type="text" class="form-control form-control-lg" placeholder="Example: marksjohn123"  />
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input v-model="email" type="text" class="form-control form-control-lg" placeholder="Example: email@example.com"  />
        </div>

        <div class="mb-3">
          <label class="form-label">No Telepon</label>
          <input v-model="phone_number" type="tel" class="form-control form-control-lg" placeholder="Example: 0812-3456-7890"  />
        </div>

        <div class="mb-3">
          <label class="form-label">password</label>
          <input v-model="password" type="password" class="form-control form-control-lg" placeholder="********"  />
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">Daftar Sekarang</button>
      </form>

      <p class="text-center mt-4 mb-0">
        Sudah punya akun?
        <RouterLink to="/" class="fw-semibold text-decoration-none"> Login </RouterLink>
      </p>
    </div>
  </div>
</template> -->


<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2' 

const fullname = ref('')
const username = ref('')
const email = ref('')
const phone_number = ref('')
const password = ref('')
const error = ref([]) // array untuk menampung pesan error
const router = useRouter()

const register = async () => {
  let errors = []

  // Fullname
  if (!fullname.value) {
    errors.push("Nama lengkap wajib diisi.")
  } else if (typeof fullname.value !== "string") {
    errors.push("Nama lengkap harus berupa teks.")
  } else if (!/^[a-zA-Z\s]+$/.test(fullname.value)) {
    errors.push("Nama lengkap hanya boleh berisi huruf dan spasi.")
  } else if (fullname.value.trim().length < 3) {
    errors.push("Nama lengkap harus lebih dari 3 karakter.")
  }

  // Username (alphanumeric, min 6 karakter)
  if (!username.value) {
    errors.push("Username wajib diisi.")
  } else if (!/^[a-zA-Z0-9]+$/.test(username.value)) {
    errors.push("Username hanya boleh huruf dan angka (tanpa spasi/simbol).")
  } else if (username.value.length < 6) {
    errors.push("Username harus lebih dari 6 karakter.")
  }

  // Email
  if (!email.value) {
    errors.push("Email wajib diisi.")
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    errors.push("Format email tidak valid.")
  }

  // Password
  if (!password.value) {
    errors.push("Password wajib diisi.")
  } else if (password.value.length < 8 || password.value.length > 16) {
    errors.push("Password harus 8–16 karakter.")
  }

  // Phone number
  if (!phone_number.value) {
    errors.push("Nomor telepon wajib diisi.")
  } else if (!/^[0-9]+$/.test(phone_number.value)) {
    errors.push("Nomor telepon hanya boleh angka.")
  }

  // jika ada error → tampilkan di alert atas form
  if (errors.length > 0) {
    error.value = errors
    return
  } else {
    error.value = [] // reset error kalau validasi lolos
  }

  // kirim ke server
  try {
    const res = await axios.post('/api/register-akun', {
      fullname: fullname.value,
      username: username.value,
      email: email.value,
      phone_number: phone_number.value,
      password: password.value
    })

    Swal.fire({
      icon: 'success',
      title: 'Registrasi Berhasil!',
      text: res.data.message,
      showConfirmButton: false,
      timer: 3000
    })

    // reset form setelah sukses
    fullname.value = ''
    username.value = ''
    email.value = ''
    phone_number.value = ''
    password.value = ''
    error.value = []

    setTimeout(() => {
      router.push('/')
    }, 3000)

  } catch (err) {
    if (err.response && err.response.data) {
      const data = err.response.data
      if (data.errors) {
        const errorMessages = Object.values(data.errors).flat()
        error.value = errorMessages
      } else {
        error.value = [data.message || 'Terjadi kesalahan!']
      }
    } else {
      error.value = ['Terjadi kesalahan server!']
    }
  }
}
</script>

<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-5 rounded-4" style="width: 450px; min-height: 600px;">
      <h2 class="mb-2 text-center fw-bold">Buat Akun Baru</h2>
      <p class="text-center text-muted mb-3">
        Daftar Sekarang Untuk Bergabung Dengan Turnament Kami
      </p>

      <!-- alert error manual -->
      <div v-if="error.length > 0" class="alert alert-danger py-2">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <small>Warning</small>
        <ul class="mb-0">
          <li v-for="(msg, idx) in error" :key="idx">{{ msg }}</li>
        </ul>
      </div>

      <form @submit.prevent="register">
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input v-model="fullname" type="text" class="form-control form-control-lg" placeholder="Example: marks john" />
        </div>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input v-model="username" type="text" class="form-control form-control-lg" placeholder="Example: marksjohn123" />
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input v-model="email" type="text" class="form-control form-control-lg" placeholder="Example: email@example.com" />
        </div>

        <div class="mb-3">
          <label class="form-label">No Telepon</label>
          <input v-model="phone_number" type="tel" class="form-control form-control-lg" placeholder="Example: 081234567890" />
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input v-model="password" type="password" class="form-control form-control-lg" placeholder="********" />
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
          Daftar Sekarang
        </button>
      </form>

      <p class="text-center mt-4 mb-0">
        Sudah punya akun?
        <RouterLink to="/" class="fw-semibold text-decoration-none"> Login </RouterLink>
      </p>
    </div>
  </div>
</template>
