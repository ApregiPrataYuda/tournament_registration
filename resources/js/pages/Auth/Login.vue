<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

const username = ref("");
const password = ref("");
const remember = ref(false);
const error = ref(""); // error global
const validationErrors = ref({}); 

axios.defaults.withCredentials = true; 

const getCsrf = async () => {
  await axios.get("/sanctum/csrf-cookie");
};

const submitLogin = async () => {
  error.value = "";
  validationErrors.value = {};

  // === Validasi Frontend ===
  if (!username.value) {
    error.value = "Username tidak boleh kosong";
    return;
  }
  if (!password.value) {
    error.value = "Password tidak boleh kosong";
    return;
  }

  if (username.value.length < 6 || username.value.length > 15) {
    error.value = "Username harus 6-15 karakter";
    return;
  }
  if (!/^[a-zA-Z0-9]+$/.test(username.value)) {
    error.value = "Username hanya boleh huruf & angka";
    return;
  }
  if (password.value.length < 8 || password.value.length > 16) {
    error.value = "Password harus 8-16 karakter";
    return;
  }

  try {
    await getCsrf();

    const res = await axios.post("/api/login", {
      username: username.value,
      password: password.value,
    });

  
    if (res.data.access_token && res.data.user) {
      
      localStorage.setItem("token", res.data.access_token); 
      localStorage.setItem("user", JSON.stringify(res.data.user));

      if (remember.value) {
        localStorage.setItem("remember", "true");
      }
      // Opsional: tampilkan pesan sukses sebentar sebelum redirect
      // alert(res.data.message);
      // router.push("/RegisterTournamen");

      // Redirect berdasarkan role
        const role = res.data.user.role; // misal role = 1 atau 2
        if (role === 1) {
          router.push("/dashboard"); // role 1 ke dashboard
        } else if (role === 2) {
          router.push("/RegisterTournamen"); // role 2 ke register team
        } else {
          error.value = "Role Anda Tidak Terdaftar di sistem kami";
        }
    } else {
      
      error.value = res.data.message || "Login gagal";
    }
  } catch (err) {
    if (err.response?.status === 422) {
      validationErrors.value = err.response.data.errors || {};
    } else {
      error.value = err.response?.data?.message || "Login gagal";
    }
  }
};
</script>


<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-5 rounded-4" style="width: 400px; min-height: 500px;">
      <h4 class="text-center fw-bold">Selamat Datang</h4>
      <h4 class="text-center fw-bold">Kembali</h4>
      <p class="text-center text-muted mb-1">Masuk ke akun Anda</p>

      <div v-if="error && !error.includes('berhasil')" class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <div>{{ error }}</div>
      </div>
      
      <form @submit.prevent="submitLogin" class="mt-1">
        <div class="mb-4">
          <label class="form-label">Username</label>
          <input
            v-model="username"
            type="text"
            class="form-control form-control-lg"
            :class="{ 'is-invalid': validationErrors.username }"
            placeholder="Masukkan username"
          />
          <div v-if="validationErrors.username" class="invalid-feedback">
            {{ validationErrors.username[0] }}
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label">Kata Sandi</label>
          <input
            v-model="password"
            type="password"
            class="form-control form-control-lg"
            :class="{ 'is-invalid': validationErrors.password }"
            placeholder="Masukkan kata sandi"
          />
          <div v-if="validationErrors.password" class="invalid-feedback">
            {{ validationErrors.password[0] }}
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" v-model="remember" id="rememberMe" />
            <label class="form-check-label" for="rememberMe">Ingat saya</label>
          </div>
          <a href="#" class="text-decoration-none small">Lupa kata sandi?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">Masuk</button>
      </form>

      <p class="text-center mt-3 mb-0">
        Belum punya akun?
        <RouterLink to="/register" class="fw-semibold text-decoration-none"> Daftar di sini </RouterLink>
      </p>
    </div>
  </div>
</template>