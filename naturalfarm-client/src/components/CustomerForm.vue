<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const customer = ref({
  name: '',
  email: '',
  phone: '',
  gender: '',
  birth_date: '',
  photo: null as File | null
})

const addresses = ref([{
  receiver_name: '',
  address_name: '',
  detail: '',
  phone: '',
  postal_code: ''
}])

const isEdit = ref(false)
const customerId = ref(null)

onMounted(async () => {
  if (route.params.id) {
    isEdit.value = true
    customerId.value = route.params.id
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/customers/${route.params.id}`)
      const customerData = response.data.data
      customer.value = {
        name: customerData.name,
        email: customerData.email,
        phone: customerData.phone,
        gender: customerData.gender,
        birth_date: customerData.birth_date,
        photo: null
      }
      addresses.value = customerData.addresses || []
    } catch (error) {
      console.error('Error fetching customer:', error)
    }
  }
})

const addAddress = () => {
  addresses.value.push({
    receiver_name: '',
    address_name: '',
    detail: '',
    phone: '',
    postal_code: ''
  })
}

const removeAddress = (index: number) => {
  addresses.value.splice(index, 1)
}

const handlePhotoChange = (event: Event) => {
  const input = event.target as HTMLInputElement
  if (input.files && input.files[0]) {
    customer.value.photo = input.files[0]
  }
}

const saveCustomer = async () => {
  try {
    const formData = new FormData()
    Object.keys(customer.value).forEach(key => {
      if (customer.value[key] !== null) {
        formData.append(key, customer.value[key])
      }
    })

    let customerResponse
    if (isEdit.value) {
      formData.append('_method', 'PUT')
      customerResponse = await axios.post(`http://127.0.0.1:8000/api/customers/${customerId.value}`, formData)
    } else {
      customerResponse = await axios.post('http://127.0.0.1:8000/api/customers', formData)
    }

    customerId.value = customerResponse.data.data.id

    for (const address of addresses.value) {
      if (isEdit.value && address.id) {
        await axios.put(`http://127.0.0.1:8000/api/address/${address.id}`, {
          ...address,
          customer_id: customerId.value
        })
      } else {
        await axios.post('http://127.0.0.1:8000/api/address', {
          ...address,
          customer_id: customerId.value
        })
      }
    }

    router.push('/')
  } catch (error) {
    console.error('Error saving customer:', error)
  }
}
</script>

<template>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">{{ isEdit ? 'Edit Customer' : 'Add Customer' }}</h5>
    </div>
    <div class="card-body">
      <form @submit.prevent="saveCustomer" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Name:</label>
          <input v-model="customer.name" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Email:</label>
          <input type="email" v-model="customer.email" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Phone:</label>
          <input v-model="customer.phone" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Gender:</label>
          <select v-model="customer.gender" class="form-select" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Birth Date:</label>
          <input type="date" v-model="customer.birth_date" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Photo:</label>
          <input type="file" @change="handlePhotoChange" accept="image/*" class="form-control" :required="!isEdit">
        </div>

        <div class="col-12">
          <h5 class="mt-4">Addresses</h5>
          <div v-for="(address, index) in addresses" :key="index" class="card mb-3">
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Receiver Name:</label>
                  <input v-model="address.receiver_name" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Address Name:</label>
                  <input v-model="address.address_name" class="form-control" required>
                </div>

                <div class="col-12">
                  <label class="form-label">Detail:</label>
                  <textarea v-model="address.detail" class="form-control" required></textarea>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Phone:</label>
                  <input v-model="address.phone" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Postal Code:</label>
                  <input v-model="address.postal_code" class="form-control" required>
                </div>

                <div class="col-12" v-if="addresses.length > 1">
                  <button type="button" class="btn btn-danger" @click="removeAddress(index)">
                    Remove Address
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <button type="button" class="btn btn-secondary me-2" @click="addAddress">Add Another Address</button>
          <button type="submit" class="btn btn-primary">{{ isEdit ? 'Update' : 'Save' }}</button>
        </div>
      </form>
    </div>
  </div>
</template>