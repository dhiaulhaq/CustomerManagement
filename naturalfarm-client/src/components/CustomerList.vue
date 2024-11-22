<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const customers = ref([])
const searchForm = ref({
  name: '',
  email: '',
  phone: '',
  gender: '',
  birth_date: ''
})

const fetchCustomers = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/customers')
    customers.value = response.data.data
  } catch (error) {
    console.error('Error fetching customers:', error)
  }
}

const searchCustomers = async () => {
  try {
    const params = new URLSearchParams()
    Object.entries(searchForm.value).forEach(([key, value]) => {
      if (value) params.append(key, value.toString())
    })
    const response = await axios.get(`http://127.0.0.1:8000/api/search?${params.toString()}`)
    customers.value = response.data.data
  } catch (error) {
    console.error('Error searching customers:', error)
  }
}

const resetSearch = () => {
  searchForm.value = {
    name: '',
    email: '',
    phone: '',
    gender: '',
    birth_date: ''
  }
  fetchCustomers()
}

const deleteCustomer = async (id: number) => {
  if (confirm('Are you sure you want to delete this customer?')) {
    try {
      await axios.delete(`http://127.0.0.1:8000/api/customers/${id}`)
      await fetchCustomers()
    } catch (error) {
      console.error('Error deleting customer:', error)
    }
  }
}

onMounted(fetchCustomers)
</script>

<template>
  <div class="customer-list">
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="card-title mb-0">Search Customers</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="searchCustomers" class="row g-3">
          <div class="col-md-4">
            <input 
              v-model="searchForm.name" 
              class="form-control" 
              placeholder="Search by name"
            >
          </div>
          <div class="col-md-4">
            <input 
              v-model="searchForm.email" 
              class="form-control" 
              placeholder="Search by email"
            >
          </div>
          <div class="col-md-4">
            <input 
              v-model="searchForm.phone" 
              class="form-control" 
              placeholder="Search by phone"
            >
          </div>
          <div class="col-md-4">
            <select v-model="searchForm.gender" class="form-select">
              <option value="">Select gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div class="col-md-4">
            <input 
              type="date" 
              v-model="searchForm.birth_date" 
              class="form-control"
            >
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary me-2">Search</button>
            <button type="button" class="btn btn-secondary" @click="resetSearch">Reset</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Customer List</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Birth Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="customer in customers" :key="customer.id">
                <td>
                  <img 
                    :src="'http://127.0.0.1:8000/storage/customers/' + customer.photo" 
                    class="rounded-circle"
                    width="50" 
                    height="50"
                    style="object-fit: cover;"
                  >
                </td>
                <td>{{ customer.name }}</td>
                <td>{{ customer.email }}</td>
                <td>{{ customer.phone }}</td>
                <td>{{ customer.gender }}</td>
                <td>{{ customer.birth_date }}</td>
                <td>
                  <div class="btn-group">
                    <router-link :to="'/customer/edit/' + customer.id" class="btn btn-sm btn-primary me-2">
                      Edit
                    </router-link>
                    <button class="btn btn-sm btn-danger" @click="deleteCustomer(customer.id)">
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>