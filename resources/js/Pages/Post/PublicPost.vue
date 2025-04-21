<script setup>
// import Comment from '../Comment.vue'
defineProps({
  posts: Object,       // posts is usually a pagination object from Laravel (not an array)
  LoggedUser: Object,   // this can be null or an object
  comment: Object,
})
</script>

<template>
<div class="container">
    <h2>Public Posts</h2>

    <div v-if="posts && posts.data && posts.data.length">
      <div v-for="post in posts.data" :key="post.id" class="mb-4 border p-3">
        <h4>{{ post.title }}</h4>
        <p>Written by - {{ post.user.name }}</p>
        <p>{{ post.content }}</p>
        <!-- <Comment/> -->
      </div>
    </div>

    <p v-else>No posts available.</p>
  </div>
</template>
