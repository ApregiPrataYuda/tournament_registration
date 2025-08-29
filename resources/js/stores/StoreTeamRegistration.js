// @/stores/TeamRegisStore.js
import { ref, reactive } from "vue";
import { defineStore } from "pinia";
import axios from "axios";
import Swal from "sweetalert2"; 

export const useTeamRegisStore = defineStore("TeamRegisList", () => {
  const baseUrlApi = "/api/list-team-register"
  const teamRegis = ref([]);
  const loadingTeams = ref(false)  
const loadingMembers = ref(false)
//   const loading = ref(false);
  const searchTeam = ref("");
  let searchTimeoutTeam = null;
  const members = ref([]);           
  const selectedTeam = ref(null);     

  const pagination = reactive({
    current_page: 1,
    per_page: 10,
    prev_page_url: null,
    next_page_url: null,
    last_page: 1,
    total: 0,
  });

  const sort = reactive({
    column: "created_at",
    direction: "desc",
  });

  const allowedSortColumns = ["name", "created_at"];

 
  const getAuthHeader = () => {
    const token = localStorage.getItem("token");
    return { Authorization: `Bearer ${token}` };
  };

  const fetchTeams = async (url = "/api/list-team-register") => {
    loadingTeams.value = true;
    try {
      const response = await axios.get(url, {
        headers: getAuthHeader(),
      });

      const result = response.data;
      const dataArray = Array.isArray(result.data)
        ? result.data
        : result.data?.data ?? [];

      teamRegis.value.splice(0, teamRegis.value.length, ...dataArray);

      const pag = result.pagination ?? result.data?.pagination
                    if (pag) {
                        pagination.current_page = pag.current_page
                        pagination.per_page = pag.per_page
                        pagination.prev_page_url = pag.prev_page_url
                        pagination.next_page_url = pag.next_page_url
                        pagination.last_page = pag.last_page
                        pagination.total = pag.total
                    }

    } catch (error) {
      console.error("Gagal fetch:", error);
    } finally {
      loadingTeams.value = false;
    }
  };

  // 🔥 Tambahkan fungsi getTeamMembers
  const getTeamMembers = async (teamId) => {
    loadingMembers.value = true;
    try {
      const res = await axios.get(`/api/list-team-register-detail/${teamId}`, {
        headers: getAuthHeader(),
      });
      members.value = res.data.data;
      selectedTeam.value = teamId;
    } catch (err) {
      console.error("Gagal ambil member:", err);
      alert("Gagal mengambil detail member.");
    } finally {
      loadingMembers.value = false;
    }
  };



  const buildUrl = () => {
                    const params = new URLSearchParams()
                            //ini code searching
                            if (searchTeam.value) {
                            params.append('search', searchTeam.value)
                            }

                            if (pagination.current_page) {
                                params.append('page', pagination.current_page)
                              }

                            if (pagination.per_page) {
                                    params.append('per_page', pagination.per_page)
                                }


                                 if (sort.column) {
                                    params.append('sort_by', sort.column)
                                    params.append('sort_dir', sort.direction)
                                }


                             return `${baseUrlApi}?${params.toString()}`
                        }


                         const searchWithDelay = (val) => {
                            clearTimeout(searchTimeoutTeam)
                            searchTeam.value = val

                            // Reset ke halaman 1 saat pencarian
                            pagination.current_page = 1

                            searchTimeoutTeam = setTimeout(() => {
                                fetchTeams(buildUrl())
                            }, 500)
                            }

                            const changePageSize = () => {
                            pagination.current_page = 1
                            fetchTeams(buildUrl())
                            }


                             const changeSorting = () => {
                                    pagination.current_page = 1
                                    fetchTeams(buildUrl())
                                }


                                  const toggleSort = (col) => {
                                    if (!allowedSortColumns.includes(col)) return  

                                    if (sort.column === col) {
                                    sort.direction = sort.direction === 'asc' ? 'desc' : 'asc'
                                    } else {
                                    sort.column = col
                                    sort.direction = 'asc'
                                    }
                                    changeSorting()
                                }


  
  const deleteTeam = async (teamId) => {
    Swal.fire({
      title: "Sure delete this team?",
      text: "Deleted data cannot be recovered!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#6c757d",
      confirmButtonText: "Yes, Delete!",
      cancelButtonText: "Cancel",
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          await axios.delete(`/api/delete-team-register/${teamId}`, {
            headers: getAuthHeader(),
          });

          // hapus dari state
          teamRegis.value = teamRegis.value.filter(
            (t) => t.team_id !== teamId
          );

          Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: "The team has been successfully deleted.",
            timer: 2000,
            showConfirmButton: false,
          });
        } catch (err) {
          console.error("Failed to delete team:", err);
          Swal.fire("Error", "Gagal menghapus tim.", "error");
        }
      }
    });
  };

  return {
    teamRegis,
    members,
    selectedTeam,
    loadingTeams,
    loadingMembers,
    searchTeam,
    searchTimeoutTeam,
    searchWithDelay,
    pagination,
    sort,
    fetchTeams,
    changeSorting,
    toggleSort,
    changePageSize,
    getTeamMembers, 
    deleteTeam
  };
});
