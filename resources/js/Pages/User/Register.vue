<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  img: null,
})

const handleSubmit = () => {
  form.post(route('user.save'), {
    preserveScroll: true,
  })
}
</script>

<template>
  <div class="container">
    <br />
    <br />
    <h2>Register</h2>

    <!-- Success Message -->
    <div v-if="$page.props.flash.success" class="alert alert-success">
      {{ $page.props.flash.success }}
    </div>

    <!-- Error Messages -->
    <div v-if="Object.keys(form.errors).length" class="alert alert-danger">
      <ul class="mb-0">
        <li v-for="(error, key) in form.errors" :key="key">
          {{ error }}
        </li>
      </ul>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
      <div class="form-group mb-3">
        <label for="name">Name</label>
        <input
          type="text"
          id="name"
          v-model="form.name"
          class="form-control"
          required
        />
      </div>

      <div class="form-group mb-3">
        <label for="email">Email</label>
        <input
          type="email"
          id="email"
          v-model="form.email"
          class="form-control"
          required
        />
      </div>

      <div class="form-group mb-3">
        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          v-model="form.password"
          class="form-control"
          required
        />
      </div>

      <div class="form-group mb-3">
        <label for="password_confirmation">Confirm Password</label>
        <input
          type="password"
          id="password_confirmation"
          v-model="form.password_confirmation"
          class="form-control"
          required
        />
      </div>

      <div class="form-group mb-3">
        <label for="img">Profile Picture</label>
        <input
          type="file"
          class="form-control-file"
          id="img"
          @change="form.img = $event.target.files[0]"
          accept="image/*"
        />
      </div>

      <button type="submit" class="btn btn-primary" :disabled="form.processing">
        Register
      </button>
    </form>
  </div>
</template>

<style scoped>
/* Add any custom styles here */
</style>
