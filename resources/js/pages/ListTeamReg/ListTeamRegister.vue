<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

const teams = ref([])
const search = ref('')
const members = ref([]) // untuk tampung data member
const selectedTeam = ref(null)
const loading = ref(true) // state loading



const token = localStorage.getItem('token')

onMounted(async () => {
  if (!token) {
    alert('Silakan login terlebih dahulu!')
    loading.value = false
    return
  }

  try {
    const res = await axios.get('/api/list-team-register', {
      headers: { Authorization: `Bearer ${token}` }
    })
    teams.value = res.data.data.data
  } catch (err) {
    console.error('Gagal mengambil data:', err)
    alert('Gagal mengambil data. Silakan cek login atau koneksi API.')
  } finally {
    loading.value = false
  }
})

// filter berdasarkan nama tim
const filteredTeams = computed(() => {
  return teams.value.filter(t =>
    t.team_name.toLowerCase().includes(search.value.toLowerCase()) ||
    t.nama_captain.toLowerCase().includes(search.value.toLowerCase()) 
  )
})

// ambil detail member
const getTeamMembers = async (teamId) => {
  try {
    const res = await axios.get(`/api/list-team-register-detail/${teamId}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    members.value = res.data.data
    selectedTeam.value = teamId
  } catch (err) {
    console.error('Gagal ambil member:', err)
    alert('Gagal mengambil detail member.')
  }
}

const deleteTeam = async (teamId) => {
  Swal.fire({
    title: 'Sure delete this team?',
    text: 'Deleted data cannot be recovered!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, Delete!',
    cancelButtonText: 'Cancel'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        await axios.delete(`/api/delete-team-register/${teamId}`, {
          headers: { Authorization: `Bearer ${token}` }
        })

        // hapus data dari state
        teams.value = teams.value.filter(t => t.team_id !== teamId)

        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The team has been successfully deleted.',
          timer: 2000,
          showConfirmButton: false
        })
      } catch (err) {
        console.error('Failed to delete team:', err)
        Swal.fire('Error', 'Gagal menghapus tim.', 'error')
      }
    }
  })
}
</script>

<template>
  <DefaultLayout>
    <div class="container my-4">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-12">
          <!-- Header -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 fw-bold">
              <i class="bi bi-people-fill me-2"></i> List Team Register Tournament
            </h1>
            <input
              v-model="search"
              type="text"
              class="form-control w-auto"
              placeholder="🔍 Search team..."
            />
          </div>

          <!-- Table inside Card -->
          <div class="card shadow-sm border-0">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>#</th>
                      <th>Team Name</th>
                      <th>Captain</th>
                      <th>Gender Captain</th>
                      <th>Email Captain</th>
                      <th>Phone Captain</th>
                      <th>Detail Member</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <!-- Kondisi Loading -->
                  <tbody v-if="loading">
                    <tr>
                      <td colspan="9" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>

                  <!-- Kondisi Ada Data -->
                  <tbody v-else-if="filteredTeams.length > 0">
                    <tr v-for="(t, index) in filteredTeams" :key="t.team_id || index">
                      <td>{{ index + 1 }}</td>
                      <td>{{ t.team_name }}</td>
                      <td>{{ t.nama_captain }}</td>
                      <td>{{ t.captain_gender }}</td>
                      <td>{{ t.email_captain }}</td>
                      <td>{{ t.phone_number_captain }}</td>
                      <td>
                        <button
                          type="button"
                          class="btn btn-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#memberModal"
                          @click="getTeamMembers(t.team_id)"
                        >
                          Member
                        </button>
                      </td>
                      <td>{{ t.created_at }}</td>
                      <td>
                        <button
                          class="btn btn-danger btn-sm"
                          @click="deleteTeam(t.team_id)"
                        >
                          <i class="bi bi-trash"></i> Delete
                        </button>
                      </td>
                    </tr>
                  </tbody>

                  <!-- Kondisi Not Found -->
                  <tbody v-else>
                    <tr>
                      <td colspan="9" class="text-center">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                          <img
                            src="https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif"
                            alt="No data found"
                            style="max-width: 300px; height: auto"
                            class="mb-3"
                          />
                          <p class="text-danger text-capitalize fw-bold h6 fst-italic">
                            <i class="fa fa-exclamation-circle me-1"></i>
                            Team Not found
                          </p>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Member -->
    <div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Member Detail
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered align-middle mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Full Name</th>
                  <th>Phone</th>
                  <th>Gender</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(m, i) in members" :key="i">
                  <td>{{ i + 1 }}</td>
                  <td>{{ m.full_name_member }}</td>
                  <td>{{ m.phone_number_member }}</td>
                  <td>{{ m.gender_member }}</td>
                </tr>
                <tr v-if="members.length === 0">
                  <td colspan="4" class="text-center text-muted">No members found</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>
