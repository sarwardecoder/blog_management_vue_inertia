<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3'; // Correct import for form handling

// Props passed from the Laravel backend
defineProps({
  posts: Array,
  loggedUser: Object
});

// Define the like, bookmark, and comment methods
const { post } = useForm(); // Use useForm for making POST requests

const handleLike = async (postId) => {
  await post(`/like/${postId}`); // Handle the like functionality
}

const handleBookmark = async (postId) => {
  await post(`/bookmark/${postId}`); // Handle the bookmark functionality
}

const handleCommentSubmit = async (postId, commentBody) => {
  await post(`/comment/${postId}`, { body: commentBody }); // Handle comment submission
}
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <!-- Navbar content -->
  </nav>

  <main role="main" class="container">
    <div class="my-3 bg-white rounded box-shadow">
      <h1 class="text-center">Our Blog Site</h1>
      <h6 class="border-bottom border-gray pb-2 mb-4">Recent updates</h6>

      <form method="GET" action="/search" class="mb-4">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Search posts..." v-model="searchQuery" />
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
          </div>
        </div>
      </form>

      <div class="row">
        <div class="col-md-4 mb-4" v-for="post in posts" :key="post.id">
          <div class="card">
            <img v-if="post.img" :src="`/${post.img}`" alt="Post Image" class="img-fluid mb-2" style="max-height: 300px" />
            <div class="card-body">
              <h5 class="card-title">Created By: {{ post.user?.name || 'Unknown User' }}</h5>
              <h6 class="card-subtitle mb-2 text-muted">Title: {{ post.title }}</h6>
              <p class="card-text">Content: {{ post.content }}</p>
              <p class="card-text">Created At: {{ post.created_at }}</p>
              <div v-if="post.tags.length">
                Tags:
                <span v-for="tag in post.tags" :key="tag.id" class="badge badge-info">{{ tag.name }}</span>
              </div>

              <button @click="handleLike(post.id)" class="btn btn-sm" :class="post.liked ? 'btn-danger' : 'btn-outline-primary'">
                {{ post.liked ? 'Unlike' : 'Like' }}
              </button>
              <span class="like-count">{{ post.likes_count }}</span> Likes

              <button @click="handleBookmark(post.id)" class="btn btn-sm" :class="post.bookmarked ? 'btn-danger' : 'btn-outline-primary'">
                {{ post.bookmarked ? 'Unbookmark' : 'Bookmark' }}
              </button>

              <div class="comment-box mt-4 p-3 border rounded bg-light">
                <h6 class="mb-3">Leave a Comment</h6>
                <form @submit.prevent="handleCommentSubmit(post.id, commentBody)">
                  <div class="form-group">
                    <textarea v-model="commentBody" rows="3" class="form-control" placeholder="Write something nice..."></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
                </form>
              </div>

              <div class="comment-list mt-3">
                <div v-for="comment in post.comments" :key="comment.id">
                  <p>{{ comment.body }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <pagination :links="paginationLinks" />
    </div>
  </main>
</template>

<style scoped>
/* Add your scoped CSS here */
</style>
