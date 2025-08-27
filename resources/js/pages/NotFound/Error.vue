<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const goHome = () => {
  router.push('/')
}
</script>

<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg border-0 rounded-4 text-center p-5" style="max-width: 500px;">
      <div class="card-body">
        <div class="mb-4">
           <i class="fas fa-exclamation-triangle text-warning" style="font-size: 5rem;"></i>
        </div>
        <h1 class="fw-bold text-danger">404</h1>
        <h4 class="fw-semibold mb-2">Halaman Tidak Ditemukan</h4>
        <p class="text-muted">
          Maaf, halaman yang kamu cari tidak tersedia atau sudah dipindahkan.
        </p>
        <button @click="goHome" class="btn btn-primary fw-bold mt-3 px-4">
          Kembali ke Halaman Login
        </button>
      </div>
    </div>
  </div>
</template>
