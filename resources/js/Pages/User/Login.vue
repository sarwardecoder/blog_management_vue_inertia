<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: ''
})

const errors = ref({})
const successMessage = ref('')
const errorMessage = ref('')

const handleSubmit = () => {
    form.post('/user/check', {
        preserveScroll: true,
        onSuccess: (usePage) => {
            successMessage.value = 'Login successful from LoginVUE!'
            form.reset()
        },
        onError: (errors) => {
            errorMessage.value = 'Login failed. Please check your credentials.'
        }
    })
}
</script>

<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Login</h2>

                        <!-- Error Alert -->
                        <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ errorMessage }}
                            <button type="button" class="btn-close" @click="errorMessage = ''"></button>
                        </div>

                        <!-- Success Alert -->
                        <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ successMessage }}
                            <button type="button" class="btn-close" @click="successMessage = ''"></button>
                        </div>

                        <form @submit.prevent="handleSubmit">
                            <!-- Email Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    v-model="form.email"
                                    class="form-control"
                                    required
                                />
                                <div v-if="form.errors.email" class="text-danger small mt-1">
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    id="password"
                                    v-model="form.password"
                                    class="form-control"
                                    required
                                />
                                <div v-if="form.errors.password" class="text-danger small mt-1">
                                    {{ form.errors.password }}
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="btn btn-primary"
                                >
                                    {{ form.processing ? 'Logging in...' : 'Login' }}
                                </button>
                            </div>

                            <!-- Register Link -->
                            <div class="text-center mt-3">
                                <a href="/user/register" class="text-decoration-none">
                                    Don't have an account? Register
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.btn-close {
    padding: 0.5rem;
}
</style>