<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { ref, defineProps } from 'vue'

const props = defineProps({
  post: Object,
  errors: Object,
  flash: Object
})

const form = useForm({
  title: props.post.title,
  content: props.post.content,
  visibility: props.post.visibility,
  img: null,
  '_method' : 'PUT'
})

const handleSubmit = () => {
  form.post(`/user/dashboard/post/update/${props.post.id}`, {
    method: 'put',
    forceFormData: true,
    preserveScroll: true
  })
}
</script>

<template>
  <div class="container">
    <h1>Edit Post</h1>

    <div v-if="usePage().props.flash.error" class="alert alert-danger">
      {{ usePage().props.flash.error }}
    </div>

    <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
      <div class="form-group mb-3">
        <label for="title">Title</label>
        <input
          type="text"
          id="title"
          class="form-control"
          v-model="form.title"
        />
        <div v-if="form.errors.title" class="text-danger">
          {{ form.errors.title }}
        </div>
      </div>

      <div class="form-group mb-3">
        <label for="content">Content</label>
        <textarea
          id="content"
          class="form-control"
          v-model="form.content"
        ></textarea>
        <div v-if="form.errors.content" class="text-danger">
          {{ form.errors.content }}
        </div>
      </div>

      <div class="form-group mb-3">
        <label for="img" class="m-2">Change Image</label>
       <button class="btn btn-primary"> <input
          type="file"
          id="img"
          class="form-control-file"
          @change="form.img = $event.target.files[0]"
          accept="image/*"
        /></button>
        <div v-if="props.post.img" class="mt-2">
          <img
            :src="`/${props.post.img}`"
            alt="Current Image"
            class="img-fluid mb-2"
            style="max-height: 300px"
          />
        </div>
      </div>

      <div class="form-group mb-4">
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
        <div v-if="form.errors.visibility" class="text-danger">
          {{ form.errors.visibility }}
        </div>
      </div>

      <button type="submit" class="btn btn-primary" :disabled="form.processing">
        Update
      </button>
    </form>
  </div>
</template>

<style scoped>
/* Optional styles */
</style>
