// import './bootstrap';
// import { createApp } from 'vue'
// import { createPinia } from 'pinia'
// import App from './App.vue'
// import router from './router'

// // import './style.css' // kalau pakai TailwindCSS
// import 'bootstrap/dist/css/bootstrap.min.css'
// import 'bootstrap/dist/js/bootstrap.bundle.min.js'
// import '@fortawesome/fontawesome-free/css/all.min.css'


// // axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// // let token = document.querySelector('meta[name="csrf-token"]');
// // if (token) {
// //     axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
// // } else {
// //     console.error("CSRF token not found!");
// // }

// import axios from "axios"

// // supaya Laravel tahu ini request AJAX
// axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// // CSRF token (kalau pakai session-cookie Sanctum)
// const csrfToken = document.querySelector('meta[name="csrf-token"]');
// if (csrfToken) {
//     axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken.content;
// } else {
//     console.warn("CSRF token not found!");
// }

// // Bearer token (kalau pakai token-based Sanctum)
// const authToken = localStorage.getItem("token");
// if (authToken) {
//   axios.defaults.headers.common["Authorization"] = `Bearer ${authToken}`;
// }

// const pinia = createPinia() 

// createApp(App).use(router).mount('#app')


import './bootstrap';
import { createApp } from 'vue'
import { createPinia } from 'pinia'   // ⬅️ tambahkan ini
import App from './App.vue'
import router from './router'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import '@fortawesome/fontawesome-free/css/all.min.css'

import axios from "axios"

// supaya Laravel tahu ini request AJAX
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// CSRF token (kalau pakai session-cookie Sanctum)
const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken.content;
} else {
    console.warn("CSRF token not found!");
}

// Bearer token (kalau pakai token-based Sanctum)
const authToken = localStorage.getItem("token");
if (authToken) {
  axios.defaults.headers.common["Authorization"] = `Bearer ${authToken}`;
}

const app = createApp(App)

const pinia = createPinia()   // ⬅️ inisialisasi pinia
app.use(pinia)                // ⬅️ daftarkan ke Vue

app.use(router)
app.mount('#app')
