<script setup>
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Link,usePage } from '@inertiajs/vue3'

const props = defineProps({
  bookmarkedPosts: Object,
  LoggedUser: Object,
})

const unbookmark = (postId) => {
  router.post(`/bookmarks/toggle/${postId}`, {}, {
    preserveScroll: true,
  })
}
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <Link class="navbar-brand" href="#"><b>Blog Site</b></Link>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"><Link class="nav-link" href="/dashboard">My Dashboard</Link></li>
        <li class="nav-item"><Link class="nav-link" href="/dashall">All Posts</Link></li>
        <li class="nav-item"><Link class="nav-link" href="/bookmarks">My Bookmarks</Link></li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <Link class="nav-link dropdown-toggle d-flex align-items-center" href="#">
            <img :src="LoggedUser?.img ? '/' + LoggedUser.img : '/uploads/default.jpg'" width="30" height="30" class="rounded-circle mr-2" alt="Profile">
            <span>{{ LoggedUser?.name }}</span>
          </Link>
        </li>
      </ul>
    </div>
  </nav>

  <main class="container my-3">
    <h1 class="text-center">My Bookmarked Posts</h1>
    <h6 class="border-bottom border-gray pb-2 mb-4">Recent updates</h6>

    <div class="row">
      <div class="col-md-4 mb-4" v-for="bookmark in bookmarkedPosts.data" :key="bookmark.id">
        <div class="card">
          <img v-if="bookmark.post.img" :src="'/' + bookmark.post.img" class="img-fluid mb-2" style="max-height: 300px;" />
          <div class="card-body">
            <h5 class="card-title">Created By: {{ bookmark.post.user?.name ?? 'Unknown' }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Title: {{ bookmark.post.title }}</h6>
            <p class="card-text">Content: {{ bookmark.post.content }}</p>
            <p class="card-text">Created At: {{ bookmark.post.created_at }}</p>

            <button @click="unbookmark(bookmark.post.id)" class="btn btn-sm btn-danger">
              Unbookmark
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination Links -->
    <div v-html="bookmarkedPosts.links"></div>
  </main>
</template>

<style scoped>
.card-img-top {
  object-fit: cover;
}
</style>
