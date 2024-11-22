<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

const numbers = ref('')
const result = ref<number[]>([])

const calculateArray = async () => {
  try {
    const numbersArray = numbers.value.split(',').map(n => parseInt(n.trim()))
    const response = await axios.post('http://127.0.0.1:8000/api/array-sum', {
      numbers: numbersArray
    })
    result.value = response.data.data
  } catch (error) {
    console.error('Error calculating array:', error)
  }
}
</script>

<template>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">Array Calculator</h5>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">Enter numbers (comma-separated):</label>
          <input 
            v-model="numbers" 
            class="form-control" 
            placeholder="Example: 10, 20, 30, 40, 50"
          >
        </div>
        
        <div class="col-12">
          <button class="btn btn-primary" @click="calculateArray">Calculate</button>
        </div>

        <div v-if="result.length" class="col-12">
          <div class="alert alert-success">
            <h6 class="alert-heading">Result:</h6>
            <p class="mb-0">{{ result }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>