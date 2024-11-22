<script setup lang="ts">
import { RouterView } from 'vue-router'
</script>

<template>
  <div class="container py-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/">Customer Management</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link class="nav-link" to="/">Customers</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/customer/create">Add Customer</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/array-calculator">Array Calculator</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <RouterView />
  </div>
</template>