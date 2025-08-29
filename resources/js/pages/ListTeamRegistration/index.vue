<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue'
// import { TeamRegisStore } from '../../stores/StoreTeamRegistration';
import { useTeamRegisStore } from '../../stores/StoreTeamRegistration'
import { ref, onMounted, watch } from 'vue';
import * as bootstrap from 'bootstrap' 

const titlePage = ref('List Team Registration');




const teamStore = useTeamRegisStore()

onMounted(async () => {
  if (!localStorage.getItem("token")) {
    alert("Silakan login terlebih dahulu!")
    return
  }
  await teamStore.fetchTeams()
})


// buka modal & ambil data members
const showMemberModal = (teamId) => {
  // buka modal dulu
  const modal = new bootstrap.Modal(document.getElementById('memberModal'))
  modal.show()

  // lalu fetch member
  teamStore.getTeamMembers(teamId)
}

watch(() => teamStore.searchTeam, (val) => {
      teamStore.searchWithDelay(val)
})


</script>



<template>
    <DefaultLayout>
  <div class="container mt-4">
    <!-- Header -->
    <div class="mb-4">
      <h6 class="text-muted mb-1">Page</h6>
      <h2 class="fw-bold">{{ titlePage }}</h2>
    </div>



    <!-- code card 1 -->
      <div class="card mb-3 shadow-sm">
        <div class="card-body d-flex flex-wrap gap-2">

          <!-- Import Excel -->
          <label class="btn btn-sm btn-outline-success mb-0">
            <i class="fas fa-file-import me-1"></i> Import Excel
            <input type="file" accept=".xlsx, .xls" hidden>
          </label>

          <!-- Export Excel -->
          <button class="btn btn-sm btn-outline-success">
            <i class="fa fa-file-excel me-1"></i> Export Excel
          </button>

          <!-- Export PDF -->
          <button class="btn btn-sm btn-outline-danger">
            <i class="fa fa-file-pdf me-1"></i> Export PDF
          </button>

          <!-- Import CSV -->
          <label class="btn btn-sm btn-outline-secondary mb-0">
            <i class="fa fa-file-import me-1"></i> Import CSV
            <input type="file" accept=".csv" hidden >
          </label>

          <!-- Export CSV -->
          <button class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-file-export me-1"></i> Export CSV
          </button>

        </div>
      </div>

          <!-- code card 2 -->
        <div class="card mb-3 shadow-sm">
          <div class="card-body">

            <!-- Baris Atas: Tampilkan dan Pencarian -->
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">

              <!-- Kiri: Tampilkan + Tombol Excel -->
              <div class="d-flex flex-column gap-2">

                <!-- Tampilkan per halaman -->
                <div class="d-flex align-items-center flex-wrap gap-2">
                  <label class="mb-0">
                    <i class="fas fa-list-ul me-1"></i> viewing:
                  </label>
                  <select class="form-select form-select-sm w-auto"
                             v-model.number="teamStore.pagination.per_page" 
                            @change="teamStore.changePageSize">
                    <option value="10">10 data per page</option>
                    <option value="25">25 data per page</option>
                    <option value="50">50 data per page</option>
                    <option value="100">100 data per page</option>
                  </select>
                </div>

                <!-- Tombol Reset -->
                <div class="d-flex flex-wrap gap-2 mt-1">
                  <button class="btn btn-sm btn-outline-white" >
                    <i class="fas fa-undo me-1"></i> Reset
                  </button>
                  
                </div>

              </div>

              <!-- Kanan: Search + Sort -->
              <div class="d-flex flex-column gap-2 align-items-end" style="min-width: 250px;">
                <!-- Pencarian -->
                <div class="input-group input-group-sm">
                  <input type="text"
                        class="form-control"
                           v-model="teamStore.searchTeam"
                        placeholder="Cari Team">
                  <span class="input-group-text bg-white"><i class="fa fa-search"></i></span>
                </div>

                <!-- Urutkan -->
                <div class="d-flex align-items-center flex-wrap gap-2 mt-1">
                  <label class="mb-0">Urutkan:</label>
                  <select class="form-select form-select-sm w-auto"
                          v-model="teamStore.sort.column" @change="teamStore.changeSorting">
                    <option value="team_name">Name Team</option>
                    <option value="created_at">Date Created</option>
                  </select>

                  <select class="form-select form-select-sm w-auto"
                          v-model="teamStore.sort.direction" @change="teamStore.changeSorting">
                    <option value="asc">To Top</option>
                    <option value="desc">To Bottom</option>
                  </select>
                </div>
              </div>

            </div>

          </div>
        </div>





     
     <!-- code card 3 -->
              <div class="card shadow-sm">
            <div class="card-body">
              <!-- Card wrapper -->
              <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                  <h5 class="mb-0 fw-semibold">{{ titlePage }}</h5>

                  <button class="btn btn-sm btn-outline-secondary" >
                    <i class="fas fa-plus me-1"></i> Add Manual Team From Admin
                  </button>

                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                      <thead class="table-light">
                        <tr class="table-secondary">
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

<tbody v-if="teamStore.loadingTeams">
  <tr>
    <td colspan="9" class="text-center py-4">
      <div class="spinner-border text-secondary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </td>
  </tr>
</tbody>

<tbody v-else-if="teamStore.teamRegis.length === 0">
  <tr>
    <td colspan="9" class="text-center">
      <div class="d-flex flex-column align-items-center justify-content-center">
        <img
          src="https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif"
          alt="No data found"
          style="max-width: 250px; height: auto;"
          class="mb-3"
        />
        <p class="text-danger fw-bold fst-italic">
          <i class="fa fa-exclamation-circle me-1"></i>
          Data Team tidak ditemukan.
        </p>
      </div>
    </td>
  </tr>
</tbody>

<tbody v-else>
  <tr v-for="(team, index) in teamStore.teamRegis" :key="team.id || index">
    <td>{{ index + 1 }}</td>
    <td>{{ team.team_name }}</td>
    <td>{{ team.nama_captain }}</td>
    <td>{{ team.captain_gender }}</td>
    <td>{{ team.email_captain }}</td>
    <td>{{ team.phone_number_captain }}</td>
    <td>
      <button class="btn btn-sm btn-primary" @click="showMemberModal(team.team_id)">
        Lihat Member
      </button>
    </td>
    <td>{{ team.created_at }}</td>
    <td>
      <button class="btn btn-danger btn-sm"  @click="teamStore.deleteTeam(team.team_id)">
        <i class="bi bi-trash"></i> Delete
      </button>
    </td>
  </tr>
</tbody>





                     
                    </table>
                  </div>
                </div>
              </div>
            </div>
            </div>

            <div class="card shadow-sm mt-2">
            <div class="card-body">

                <!-- start code pagination -->
                <div class="mt-1 d-flex justify-content-between align-items-center">
                <button
                  class="btn btn-outline-danger"
                   :disabled="!teamStore.pagination.prev_page_url || teamStore.loadingTeams"
                  @click="teamStore.fetchTeams(teamStore.pagination.prev_page_url)"
                >
                  <i class="fa-solid fa-circle-chevron-left"></i> Previous
                </button>

                <span class="text-muted">
                   Page {{ teamStore.pagination.current_page }} of {{ teamStore.pagination.last_page }}
                </span>

                <button
                  class="btn btn-outline-primary"
                 :disabled="!teamStore.pagination.next_page_url || teamStore.loading"
                  @click="teamStore.fetchTeams(teamStore.pagination.next_page_url)"
                >
                  Next <i class="fa-solid fa-circle-chevron-right"></i>
                </button>
              </div>

              <p class="text-muted text-center mt-2">
                Menampilkan {{ teamStore.teamRegis.length }} data | pada halaman ke-{{ teamStore.pagination.current_page }} | dari total {{ teamStore.pagination.total }} data
              </p>
                <!-- end code pagination -->
              </div>
            </div>
          </div>

      
     <!-- Modal Member -->
    <div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Member Detail - Tim {{ teamStore.selectedTeam }}
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


              <!-- start code tampilkan laoding saat data sedang di load -->
                  <tbody v-if="teamStore.loadingMembers">
                  <tr>
                    <td colspan="6" class="text-center py-3">
                    <div class="spinner-border text-secondary" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    </td>
                  </tr>
                </tbody>
                <!-- end code tampilkan laoding saat data sedang di load -->
                

                  <!-- start code tampilkan jika data tersedia -->
                <tbody v-else-if="teamStore.teamRegis.length === 0">
                  <tr>
                    <td colspan="6" class="text-center">
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <img
                          src="https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif"
                          alt="No data found"
                          style="max-width: 300px; height: auto;"
                          class="mb-3"
                        />
                        <p class="text-danger text-capitalize font-weight-bold h6 fst-italic">
                          <i class="fa fa-exclamation-circle mr-1"></i>
                          Data tidak ditemukan.
                        </p>
                      </div>
                    </td>
                  </tr>
                </tbody>
                <!-- end code tampilkan jika data tersedia -->



              <tbody>
                <tr v-for="(m, i) in teamStore.members" :key="i">
                  <td>{{ i + 1 }}</td>
                  <td>{{ m.full_name_member }}</td>
                  <td>{{ m.phone_number_member }}</td>
                  <td>{{ m.gender_member }}</td>
                </tr>
                <tr v-if="teamStore.members.length === 0">
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


<style scoped>
.tw1 {
    width: 3%;
}

.tw2 {
    width: 8%;
}
</style>