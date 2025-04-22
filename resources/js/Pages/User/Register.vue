<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    img: null,
})

const handleSubmit = () => {
    form.post('/user/save', {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Create your account</h2>
                        
                        <form @submit.prevent="handleSubmit">
                            <!-- Name Input -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="form-control"
                                    required
                                />
                                <div v-if="form.errors.name" class="text-danger small mt-1">
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <!-- Email Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="form-control"
                                    required
                                />
                                <div v-if="form.errors.email" class="text-danger small mt-1">
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="form-control"
                                    required
                                />
                                <div v-if="form.errors.password" class="text-danger small mt-1">
                                    {{ form.errors.password }}
                                </div>
                            </div>

                            <!-- Confirm Password Input -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="form-control"
                                    required
                                />
                            </div>

                            <!-- Profile Image Input -->
                            <div class="mb-4">
                                <label for="img" class="form-label">Profile Image</label>
                                <input
                                    id="img"
                                    type="file"
                                    @input="form.img = $event.target.files[0]"
                                    accept="image/*"
                                    class="form-control"
                                />
                                <div v-if="form.errors.img" class="text-danger small mt-1">
                                    {{ form.errors.img }}
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="btn btn-primary"
                                >
                                    {{ form.processing ? 'Registering...' : 'Register' }}
                                </button>
                            </div>

                            <!-- Login Link -->
                            <div class="text-center mt-3">
                                <Link href="/login" class="text-decoration-none">
                                    Already have an account? Login
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.min-vh-100 {
    min-height: 100vh;
}
</style>