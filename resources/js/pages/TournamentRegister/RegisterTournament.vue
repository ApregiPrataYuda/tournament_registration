
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2' 
const router = useRouter()

const teamName = ref('')
const captainName = ref('')
const captainGender = ref('')
const captainPhone = ref('')
const memberName = ref('')
const memberPhone = ref('')
const memberGender = ref('')
const errors = ref({}) // untuk error spesifik per field
const generalError = ref('') // untuk error umum dari server
const successMessage = ref('') // untuk pesan sukses

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user'))
  if (user) {
    captainName.value = user.fullname
    captainPhone.value = user.phone_number
  }
})

// Proses simpan tim
const submitForm = async () => {
  // Reset semua pesan status dan error
  errors.value = {}
  generalError.value = ''
  successMessage.value = ''

   // === Validasi Frontend ===
  let isValid = true;

  // 1. Validasi Nama Tim
  if (!teamName.value) {
    errors.value.team_name = ['Nama tim tidak boleh kosong.'];
    isValid = false;
  } else if (teamName.value.length < 4 || teamName.value.length > 15) {
    errors.value.team_name = ['Nama tim harus 4-15 karakter.'];
    isValid = false;
  } else if (!/^[a-zA-Z0-9_]+$/.test(teamName.value)) {
    errors.value.team_name = ['Nama tim hanya boleh mengandung huruf, angka, dan underscore.'];
    isValid = false;
  }

  // 2. Validasi Nama Anggota
  if (!memberName.value) {
    errors.value.full_name_member = ['Nama anggota tidak boleh kosong.'];
    isValid = false;
  } else if (!/^[a-zA-Z\s]+$/.test(memberName.value)) {
    errors.value.full_name_member = ['Nama anggota hanya boleh mengandung huruf dan spasi.'];
    isValid = false;
  }

  // 3. Validasi Nomor Telepon Anggota
  if (!memberPhone.value) {
    errors.value.phone_number_member = ['Nomor telepon anggota tidak boleh kosong.'];
    isValid = false;
  } else if (memberPhone.value.length < 7 || memberPhone.value.length > 14) {
    errors.value.phone_number_member = ['Nomor telepon harus 7-14 karakter.'];
    isValid = false;
  } else if (!/^[0-9]+$/.test(memberPhone.value)) {
    errors.value.phone_number_member = ['Nomor telepon hanya boleh mengandung angka.'];
    isValid = false;
  }

  // 4. Validasi Jenis Kelamin
  if (!captainGender.value) {
    errors.value.captain_gender = ['Pilih jenis kelamin kapten.'];
    isValid = false;
  }
  if (!memberGender.value) {
    errors.value.gender_member = ['Pilih jenis kelamin anggota.'];
    isValid = false;
  }

  // Jika validasi gagal, hentikan proses
  if (!isValid) {
    Swal.fire({
      icon: 'error',
      title: 'Validasi Gagal!',
      text: 'Mohon periksa kembali data yang Anda masukkan.',
    });
    return;
  }
  // === End Validasi Frontend ===

  try {
    const payload = {
      team_name: teamName.value,
      full_name_member: memberName.value,
      phone_number_member: memberPhone.value,
      captain_gender: captainGender.value,
      gender_member: memberGender.value,
    }

    const response = await axios.post('/api/add-team-register', payload, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })

    Swal.fire({
      icon: 'success',
      title: 'Pendaftaran Berhasil!',
      text: response.data.message || 'Selamat, Tim anda berhasil terdaftar untuk turnament ini!',
      showCancelButton: true, // Tombol Logout
      confirmButtonText: 'OK', // Tombol standar untuk lanjut
      cancelButtonText: 'Logout', // Tombol logout
      timer: 6000,
      customClass: {
        popup: 'animated tada'
      }
      }).then((result) => {
        if (result.isConfirmed) {
          // Jika klik OK
          router.push('/RegisterTournamen')
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          // Jika klik Logout
          localStorage.removeItem("user") // Hapus data user
          router.push('/') // Redirect ke halaman login
        }
      })


    teamName.value = ''
    captainGender.value = ''
    memberName.value = ''
    memberPhone.value = ''
    memberGender.value = ''


  } catch (error) {
    console.error('Error:', error)
    if (error.response) {
      console.error('Error response data:', error.response.data)
      if (error.response.status === 422 && error.response.data.errors) {
        errors.value = error.response.data.errors
      } else {
        generalError.value = error.response.data.message || 'Terjadi kesalahan saat mendaftar.'
      }
    } else {
      generalError.value = 'Terjadi kesalahan jaringan atau server.'
    }
  }
}

// Logout
const tokenLogout = localStorage.getItem('token')
console.log(tokenLogout);

const logout = async () => {
  if (!tokenLogout) {
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
          Authorization: `Bearer ${tokenLogout}`
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

<template>
  <div class="vh-100 bg-light d-flex flex-column">
    <!-- Header dengan tombol Logout -->
    <div class="d-flex justify-content-end p-3">
      <button class="btn btn-danger fw-semibold px-4" @click="logout">Logout</button>
    </div>

    <!-- Konten Form -->
    <div class="flex-grow-1 d-flex justify-content-center align-items-center">
      <div class="card shadow-lg p-5 rounded-4" style="max-width: 600px; width: 100%;">
        
        <!-- Header -->
        <h2 class="text-center fw-bold mb-2">Pendaftaran Turnamen Tim</h2>
        <p class="text-center text-muted mb-2">
          Isi data tim Anda untuk mendaftar.
        </p>

        <!-- Section Title -->
        <h5 class="fw-semibold text-center mb-2">Data Tim</h5>

         <div v-if="generalError" class="alert alert-danger d-flex align-items-center" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <div>{{ generalError }}</div>
        </div>

        <form  @submit.prevent="submitForm">
          <!-- Nama Tim -->
          <div class="mb-3">
            <label class="form-label">Nama Tim</label>
            <input v-model="teamName" type="text" class="form-control rounded-3" />
            <small v-if="errors.team_name" class="text-danger">
              {{ errors.team_name[0] }}
            </small>
          </div>
          <hr class="my-4 border-1 rounded opacity-20">

          <!-- Kapten Tim -->
          <h6 class="fw-bold mt-4">Kapten Tim</h6>
          <div class="mb-3">
          <label class="form-label">Nama Lengkap <small class="text-warning">(automatic)</small></label>
          <input v-model="captainName" type="text" class="form-control rounded-3" readonly/>
        </div>
        <div class="mb-3">
          <label class="form-label">Nomor Telepon <small class="text-warning">(automatic)</small></label>
          <input v-model="captainPhone" type="text" class="form-control rounded-3" readonly/>
        </div>
          <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select v-model="captainGender" class="form-select rounded-3">
              <option value="">Pilih Jenis Kelamin</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
             <small v-if="errors.captain_gender" class="text-danger">
              {{ errors.captain_gender[0] }}
            </small>
          </div>

          <hr class="my-4 border-1 rounded opacity-20">

          <!-- Anggota Tim -->
          <h6 class="fw-bold mt-4">Anggota Tim</h6>
          
          <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input v-model="memberName" type="text" class="form-control rounded-3" />
             <small v-if="errors.full_name_member" class="text-danger">
              {{ errors.full_name_member[0] }}
            </small>
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input v-model="memberPhone" type="text" class="form-control rounded-3" />
             <small v-if="errors.phone_number_member" class="text-danger">
              {{ errors.phone_number_member[0] }}
            </small>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select v-model="memberGender" class="form-select rounded-3">
              <option value="">Pilih Jenis Kelamin</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
            <small v-if="errors.gender_member" class="text-danger">
            {{ errors.gender_member[0] }}
          </small>
          </div>

          <!-- Link -->
          <div class="text-center mt-3">
            <a href="#" data-bs-toggle="modal" data-bs-target="#aturanModal" class="small text-decoration-none">
              Baca Aturan & Regulasi Turnamen
            </a>
          </div>

          <!-- Button -->
          <button type="submit" class="btn w-100 mt-4 text-white fw-semibold"
            style="background: linear-gradient(90deg, #7b2ff7, #f107a3); border-radius: 12px;">
            Daftarkan Tim
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Aturan -->
  <div class="modal fade" id="aturanModal" tabindex="-1" aria-labelledby="aturanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div
          class="modal-header text-white"
          style="background: linear-gradient(90deg, #7b2ff7, #f107a3); border-radius: 12px;"
        >
          <h5 class="modal-title fw-bold" id="aturanLabel">📜 Aturan & Regulasi Turnamen</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <ol class="list-group list-group-numbered">
            <li class="list-group-item">Peserta wajib menggunakan akun resmi dan data valid.</li>
            <li class="list-group-item">Dilarang menggunakan cheat, hack, atau software ilegal.</li>
            <li class="list-group-item">Keputusan panitia bersifat mutlak dan tidak dapat diganggu gugat.</li>
            <li class="list-group-item">Peserta wajib hadir 10 menit sebelum pertandingan dimulai.</li>
            <li class="list-group-item">Pelanggaran aturan dapat berakibat diskualifikasi.</li>
          </ol>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
        </div>
      </div>
    </div>
  </div>
</template>
