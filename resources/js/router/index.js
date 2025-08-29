// import { createRouter, createWebHistory } from 'vue-router'
// import Dashboard from '@/pages/Dashboard/File.vue'
// import Login from '../pages/Auth/Login.vue'
// import Register from '../pages/Auth/Register.vue'
// import RegisterTournament from '../pages/TournamentRegister/RegisterTournament.vue'
// import NotFound  from '../pages/NotFound/Error.vue'
// import ListTeamRegister  from '../pages/ListTeamReg/ListTeamRegister.vue'


// const routes = [
//   { path: '/', component: Login },
//   { path: '/register', component: Register },
//   { path: '/RegisterTournamen', component: RegisterTournament },
//   { path: '/Dashboard', component: Dashboard },
//   { path: '/ListTeamRegister', component: ListTeamRegister },

//   { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }
// ]

// const router = createRouter({
//   history: createWebHistory(),
//   routes,
// })
// router.beforeEach((to, from, next) => {
//   const isLoggedIn = !!localStorage.getItem("user") // contoh cek login
//   if (to.meta.requiresAuth && !isLoggedIn) {
//     next('/') // redirect ke login
//   } else {
//     next() // lanjutkan
//   }
// })
// export default router


import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '@/pages/Dashboard/File.vue'
import Login from '../pages/Auth/Login.vue'
import Register from '../pages/Auth/Register.vue'
import RegisterTournament from '../pages/TournamentRegister/RegisterTournament.vue'
import NotFound  from '../pages/NotFound/Error.vue'
import ListTeamRegister  from '../pages/ListTeamReg/ListTeamRegister.vue'
import ListTeamRegistration from '../pages/ListTeamRegistration/index.vue'

const routes = [
  { path: '/', component: Login },
  { path: '/register', component: Register },
  { 
    path: '/RegisterTournamen', 
    component: RegisterTournament,
    meta: { requiresAuth: true }  
  },
  { 
    path: '/Dashboard', 
    component: Dashboard,
    meta: { requiresAuth: true }  
  },
  { 
    path: '/ListTeamRegister', 
    component: ListTeamRegister,
    meta: { requiresAuth: true }   
  },

  { 
    path: '/ListTeamRegistration', 
    component: ListTeamRegistration,
    meta: { requiresAuth: true }   
  },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation Guard
router.beforeEach((to, from, next) => {
  const isLoggedIn = !!localStorage.getItem("user") // contoh cek login
  if (to.meta.requiresAuth && !isLoggedIn) {
    next('/') // redirect ke login
  } else {
    next() // lanjutkan
  }
})

export default router
