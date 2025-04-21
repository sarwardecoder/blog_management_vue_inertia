<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  content: '',
  img: null,
  visibility: 'public',
  tags: ''
})

const handleSubmit = () => {
  form.post('/user/dashboard/post/store', {
    forceFormData: true,
    preserveScroll: true
  })
}
</script>

<template>
  <div class="container mt-5">
    <h2>Create a New Post</h2>

    <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
      <div class="form-group mb-3">
        <label for="title">Title</label>
        <input
          type="text"
          id="title"
          class="form-control"
          v-model="form.title"
          required
        />
        <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
      </div>

      <div class="form-group mb-3">
        <label for="content">Content</label>
        <textarea
          id="content"
          class="form-control"
          rows="4"
          v-model="form.content"
          required
        ></textarea>
        <div v-if="form.errors.content" class="text-danger">{{ form.errors.content }}</div>
      </div>

      <div class="form-group mb-3">
        <label for="img">Image</label>
        <input
          type="file"
          id="img"
          class="form-control-file"
          accept="image/*"
          @change="form.img = $event.target.files[0]"
        />
        <div v-if="form.errors.img" class="text-danger">{{ form.errors.img }}</div>
      </div>

      <div class="form-group mb-3">
        <label for="visibility">Visibility</label>
        <select
          id="visibility"
          class="form-control"
          v-model="form.visibility"
          required
        >
          <option value="public">Public</option>
          <option value="private">Private</option>
        </select>
        <div v-if="form.errors.visibility" class="text-danger">{{ form.errors.visibility }}</div>
      </div>

      <div class="form-group mb-4">
        <label for="tags">Tags (comma separated)</label>
        <input
          type="text"
          id="tags"
          class="form-control"
          placeholder="e.g. Laravel, Vue, PHP"
          v-model="form.tags"
        />
        <div v-if="form.errors.tags" class="text-danger">{{ form.errors.tags }}</div>
      </div>

      <button
        type="submit"
        class="btn btn-primary"
        :disabled="form.processing"
      >
        Create Post
      </button>
    </form>
  </div>
</template>

<style scoped>
</style>
