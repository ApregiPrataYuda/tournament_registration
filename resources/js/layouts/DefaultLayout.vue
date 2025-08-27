<template>
  <div class="d-flex">
    <!-- Sidebar (desktop) -->
    <div class="d-none d-lg-block">
      <Sidebar />
    </div>

    <!-- Offcanvas Sidebar (mobile) -->
    <div 
      class="offcanvas offcanvas-start bg-dark text-white" 
      tabindex="-1" 
      id="sidebarOffcanvas"
    >
      <Sidebar />
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 d-flex flex-column">
      <Navbar />

      <main class="flex-grow-1 py-4">
        <div class="container">
          <slot />
        </div>
      </main>

      <Footer />
    </div>
  </div>
</template>

<script setup>
import Sidebar from '@/components/Sidebar.vue'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
</script>
